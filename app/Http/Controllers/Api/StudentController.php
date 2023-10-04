<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use App\Models\Exercise;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Lession;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Teste;
use DateTime;

class StudentController extends Controller
{
    public function courses()
    {
        $id = Auth::id();
        $courses = DB::table('course_user')
            ->leftJoin('users', 'course_user.user_id', '=', 'users.id')
            ->leftJoin('courses', 'course_user.course_id', '=', 'courses.id')
            ->leftJoin('universities', 'courses.university_id', '=', 'universities.id')
            ->select(
                'courses.id',
                'courses.name',
                'courses.degree',
                'courses.duration',
                'courses.description', 
                'course_user.status',
                'universities.photo as university'
            )->where('users.id', $id)
            ->get();
        return response()->json(['courses' => $courses], Response::HTTP_OK);
    }

    public function course(string $course_id)
    {
        $id = Auth::id();

        // Encontrar o curso
        $course = Course::find($request->course_id);

        // Retorna a resposta de erro
        if (!$course) {
            return response()->json(['message' => 'Curso não encontrado'], Response::HTTP_NOT_FOUND);
        }

        $courses = DB::table('course_user')
            ->leftJoin('users', 'course_user.user_id', '=', 'users.id')
            ->leftJoin('courses', 'course_user.course_id', '=', 'courses.id')
            ->leftJoin('universities', 'courses.university_id', '=', 'universities.id')
            ->select(
                'courses.id',
                'courses.name',
                'courses.degree',
                'courses.duration',
                'course_user.status',
                'universities.photo as university'
            )->where('users.id', $id)
            ->where('courses.id', $course_id)
            ->get();
        return response()->json(['courses' => $courses], Response::HTTP_OK);
    }

    public function subjects()
    {}

    public function lessions(string $id)
    {
        // Encontrar o estudante
        $student = Auth::user();

        // Encontrar a disciplina
        $subject = Subject::find($id);

        // Retorna a resposta de erro
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Encontrar as aulas da disciplina
        $lessionsSubject = $subject->lessions;

        $lessions = [];
        $found = false;
        foreach ($lessionsSubject as $lession) {
            if ($student->hasAttendedLesson($lession->id)) {
                $status = $student->statusLesson($lession->id)->pivot->status;
                if($status === 'Em Progresso'){
                    $found = true;
                }
            } else {
                if(!$found){
                    $status = 'Iniciar Aula';
                    $found = true;
                }else {
                    $status = 'Indísponivel';
                }
            }
            array_push($lessions, [
                'id'              => $lession->id,
                'name'            => $lession->name,
                'file_path'       => $lession->file_path,
                'subject_id'      => $lession->subject_id,
                'thematicunit_id' => $lession->thematicunit_id,
                'status'          => $status
            ]);
        }
        
        // Retorna a resposta de sucesso
        return response()->json(['lessions' => $lessions], Response::HTTP_OK);
    }

    public function lession (string $id)
    {
        // Encontrar o estudante
        $student = Auth::user();

        // Encontrar a aula
        $lession = $student->lessions()->where('lession_id', $id)->first();

        // Retorna a resposta de erro
        if (!$lession) {
            return response()->json(['message' => 'Aula não encontrada'], Response::HTTP_NOT_FOUND);
        }

        $university = $lession->subject->course->university->photo;
        $subject    = $lession->subject;
        $quiz       = Quiz::where('lession_id',$lession->id)->where('user_id',$student->id)->get();
        $summaries = [];
        $filePath = '';
        foreach ($lession->summary as $summary) {
            $path = $summary->file_path;
        
            // Verificar se o arquivo existe
            if (Storage::exists($path)) {
                // Ler o conteúdo do arquivo
                $conteudo = Storage::get($path);
                
                // Converter o conteúdo em base64
                // $base64 = base64_encode($conteudo);
                $filePath = base64_encode($conteudo);
                // return response()->json(['base64' => $base64]);
            }
            $types = array('webm','mp4','mp3');
            if (in_array($summary->type, $types)) {
                $filePath = $summary->file_path;
            }
            
            array_push($summaries, [
                'name' => $summary->name,
                'type' => $summary->type,
                'file_path' => $filePath
                // 'file_path' => $base64File
            ]);
        }

        $response = [
            'lession'    => [
                'id'         => $lession->id,
                'name'       => $lession->name,
                'file_path'  => $lession->file_path,
                'type'     => $lession->type,
                'status'     => $lession->pivot->status,
                'started_at' => $lession->pivot->started_at,
                'ended_at'   => $lession->pivot->ended_at,
                'summary'    => $summaries, 
            ],
            'subject'        => [
                'name'       => $subject->name,
                'type'       => $subject->type,
                'time'       => $subject->time,
                'credits'    => $subject->credits,
                'university' => $university,
            ],
            'quiz'           => $quiz,
        ];

        // Retorna a resposta de sucesso
        return response()->json($response, Response::HTTP_OK);
    }

    public function startLession (Request $request)
    {
        // Encontrar o estudante
        $student = Auth::user();

        // Encontrar a aula
        $lession = Lession::find($request->lession_id);

        // Retorna a resposta de erro
        if (!$lession) {
            return response()->json(['message' => 'Aula não encontrada'], Response::HTTP_NOT_FOUND);
        }
        
        if(!$student->hasAttendedLesson($lession->id)){
            $student->lessions()->attach($lession->id);
            $questions = $lession->questions;
            $totalQuestions = count($questions);
            if($totalQuestions > 0){
                $equalQuote = 20 / $totalQuestions;
                foreach ($questions as $key => $question) {
                    $quiz = new Quiz();
                    $quiz->question   = $question->question;
                    $quiz->optionA    = $question->optionA;
                    $quiz->optionB    = $question->optionB;
                    $quiz->optionC    = $question->optionC;
                    $quiz->optionD    = $question->optionD;
                    $quiz->correct    = $question->correct;
                    $quiz->score      = $equalQuote;
                    $quiz->lession_id = $lession->id;
                    $quiz->subject_id = $lession->subject->id;
                    $quiz->user_id    = $student->id;
                    $quiz->save();
                    if($key === count($questions)-3){
                        break;
                    }
                }
            }
        }

        $response = $student->lessions()->where('lession_id', $lession->id)->first();
        // Retorna a resposta de sucesso
        return response()->json($response, Response::HTTP_OK);
    }

    public function getQuizMarks(Request $request)
    {
        // Encontrar o estudante
        $student = Auth::user();
        $lession = Lession::find($request->lession);
        $quiz = Quiz::where('lession_id', $lession->id)->where('user_id',$student->id)->get();
        $marks = 0;
        $corrects = 0;
        foreach ($quiz as $question) {
            if($question->correct === $question->answer){
                $marks += $question->score;
                $corrects++;
            }
            $date = $question->updated_at;
        }
        $response = [
            'school'   => $lession->subject->course->university->name,
            'course'   => $lession->subject->course->name,
            'subject'  => $lession->subject->name,
            'corrects' => $corrects,
            'total'    => count($quiz),
            'marks'    => $marks,
            'date'     => date_format($date,'d-m-Y')
        ];
        return response()->json($response, Response::HTTP_OK);
    }
    
    public function quizAnswers(Request $request)
    {
        $answers = $request->answers;
        $marks = 0;
        foreach ($answers as $questionId => $answer) {
            Quiz::where('id', $questionId)->update(['answer' => $answer]);
            $question = Quiz::find($questionId);
            if($question->correct === $question->answer){
                $marks += $question->score;
            }
        }
        // Encontrar o estudante
        $student = Auth::user();
        // Actualiza a estado da aula para concluido
        $student->lessions()->updateExistingPivot($question->lession_id, ['status' => 'Concluída','ended_at' => now()]);
        $lession = $student->lessions()->where('lession_id', $question->lession_id)->first();
        $response = [
            'lession'    => [
                'id'         => $lession->id,
                'name'       => $lession->name,
                'file_path'  => $lession->file_path,
                'type'       => $lession->type,
                'status'     => $lession->pivot->status,
                'started_at' => $lession->pivot->started_at,
                'ended_at'   => $lession->pivot->ended_at,
                'summary'    => [], 
            ],
            'marks' => $marks
        ];
        return response()->json($response, Response::HTTP_OK);        
    }
    
    public function endLession (Request $request)
    {
        // Encontrar o estudante
        $student = Auth::user();

        // Encontrar a aula
        $lession = Lession::find($request->lession_id);

        // Retorna a resposta de erro
        if (!$lession || !$student->hasAttendedLesson($request->lession_id)) {
            return response()->json(['message' => 'Aula não encontrada'], Response::HTTP_NOT_FOUND);
        }
        // Actualiza a estado da aula para concluido
        $response = $student->lessions()->updateExistingPivot($request->lession_id, ['status' => 'Concluída','ended_at' => now()]);

        // Retorna a resposta de sucesso
        return response()->json($response, Response::HTTP_OK);
    }

    public function getTests(string $id)
    {
        $student = Auth::user();

        // Encontrar a disciplina
        $subject = Subject::find($id);

        // Retorna a resposta de erro
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Encontrar os testes da disciplina
        $testes = $subject->testes;

        // Encontrar as aulas da disciplina
        $data = [];
        $found = false;
        foreach ($testes as $teste) {
            $marks = 0;
            $date = null;
            if ($student->hasAttendedLesson($teste->after_lession_id)) {
                if ($student->hasAttendedTeste($teste->id)) {
                    $status = $student->statusTeste($teste->id)->pivot->status;
                    $exercises = Exercise::where('teste_id', $teste->id)->where('user_id',$student->id)->get();
                    foreach ($exercises as $question) {
                        if($question->correct === $question->answer){
                            $marks += $question->score;
                        }
                        $date = date_format($question->updated_at,'d-m-Y');
                    }
                }else{
                    if($teste->name === 'Exame Normal'){
                        $status = 'Calcular media';
                    }elseif($teste->name === 'Exame de Recorrência'){
                        $status = 'Verificar nota de exame normal';
                    }else{
                        $status = 'Iniciar Avaliação';
                    }
                }
            }else {
                $status = 'Indísponivel';
            }
            array_push($data, [
                'id'              => $teste->id,
                'name'            => $teste->name,
                'marks'           => $teste->score($student->id),
                'date'            => $date,
                'status'          => $status
            ]);
        }
        // Calculo de média
        $media = 15;
        $response = [
            'testes' => $data,
            'media'  => $media,
            'results' => $student->results($id),
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function startTest (Request $request)
    {
        // Encontrar o estudante
        $student = Auth::user();
        // Encontrar o teste
        $teste = Teste::find($request->teste_id);
        
        // Retorna a resposta de erro
        if (!$teste) {
            return response()->json(['message' => 'Avaliação não encontrada'], Response::HTTP_NOT_FOUND);
        }
        
        $totalQuestions = count($teste->questions);
        if($totalQuestions > 0){
            $equalQuote = 20 / $totalQuestions;
            foreach ($teste->questions as $key => $question) {
                $exercise = new Exercise();
                $exercise->question   = $question->question;
                $exercise->optionA    = $question->optionA;
                $exercise->optionB    = $question->optionB;
                $exercise->optionC    = $question->optionC;
                $exercise->optionD    = $question->optionD;
                $exercise->correct    = $question->correct;
                $exercise->score      = $equalQuote;
                $exercise->teste_id   = $teste->id;
                $exercise->user_id    = $student->id;
                $exercise->save();
            }
            $student->testes()->updateExistingPivot($teste->id, ['status' => 'Em Progresso','started_at' => now()]);
        }
        $response = $student->testes()->where('teste_id', $teste->id)->first();
        // Retorna a resposta de sucesso
        return response()->json($response, Response::HTTP_OK);
    }

    public function teste (string $id)
    {
        // Encontrar o estudante
        $student = Auth::user();

        // Encontrar o test
        $teste = $student->testes()->where('teste_id', $id)->first();

        // Retorna a resposta de erro
        if (!$teste) {
            return response()->json(['message' => 'Avalição não encontrada'], Response::HTTP_NOT_FOUND);
        }
        $exercises = Exercise::where('teste_id', $id)->where('user_id', $student->id)->get();
        
        $university = $teste->subject->course->university->photo;
        $subject    = $teste->subject;
        

        $response = [
            'teste'    => [
                'id'         => $teste->id,
                'name'       => $teste->name,
                'status'     => $teste->pivot->status,
                'started_at' => $teste->pivot->started_at,
                'ended_at'   => $teste->pivot->ended_at,
            ],
            'subject'        => [
                'name'       => $subject->name,
                'type'       => $subject->type,
                'time'       => $subject->time,
                'credits'    => $subject->credits,
                'university' => $university,
            ],
            'exercises'           => $exercises,
        ];
        // Retorna a resposta de sucesso
        return response()->json($response, Response::HTTP_OK);
    }

    public function testAnswers(Request $request)
    {
        $answers = $request->answers;
        $marks = 0;
        foreach ($answers as $questionId => $answer) {
            Exercise::where('id', $questionId)->update(['answer' => $answer]);
            $question = Exercise::find($questionId);
            if($question->correct === $question->answer){
                $marks += $question->score;
            }
        }
        // Encontrar o estudante
        $student = Auth::user();
        // Actualiza a estado da aula para concluido
        $student->testes()->updateExistingPivot($question->teste_id, ['status' => 'Concluída','ended_at' => now()]);
        $teste = $student->testes()->where('teste_id', $question->teste_id)->first();
        $response = [
            'teste'    => [
                'id'         => $teste->id,
                'name'       => $teste->name,
                'status'     => $teste->pivot->status,
                'started_at' => $teste->pivot->started_at,
                'ended_at'   => $teste->pivot->ended_at,
            ],
            'marks' => $marks
        ];
        return response()->json($response, Response::HTTP_OK);
    }
    
    public function getTestMarks(string $id)
    {
        // Encontrar o estudante
        $student = Auth::user();
        $teste = $student->testes()->where('teste_id', $id)->first(); 
        $exercises = Exercise::where('teste_id', $teste->id)->where('user_id',$student->id)->get();
        $marks = 0;
        $corrects = 0;
        foreach ($exercises as $question) {
            if($question->correct === $question->answer){
                $marks += $question->score;
                $corrects++;
            }
        }
        $response = [
            'school'   => $teste->subject->course->university,
            'course'   => $teste->subject->course,
            'subject'  => $teste->subject->name,
            'corrects' => $corrects,
            'total'    => count($exercises),
            'marks'    => $marks,
            'teste'    => $teste->name,
            // 'date'     => date_format($date,'d-m-Y')
            'date'     => date_format(new DateTime($teste->pivot->ended_at),'d-m-Y')
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function certificates(string $type)
    {
        // Encontrar o estudante
        $student = Auth::user();
        $response = [];
        switch ($type) {
            case 'courses':
                $courses = $student->courses;
                $data = [];
                foreach ($courses as $key => $course) {
                    $aproved = true;
                    $sum = 0;
                    $total = count($course->subjects);;
                    foreach ($course->subjects as $key => $subject) {
                        if(!($student->results($subject->id)['status'] === 'Dispensado' || $student->results($subject->id)['status'] === 'Aprovado')){
                            $aproved = false;
                            break;
                        }else{
                            $sum += $student->results($subject->id)['media'];
                        }
                    }
                    if($aproved){
                        array_push($data, [
                            'id'       => $course->id,
                            'media'    => $sum/$total,
                            'school'   => $course->university,
                            'course'   => $course->name
                        ]);
                    }
                }
                $response = ['courses' => $data];
                break;
            case 'subjects':
                $data = [];
                $subjects = $student->subjects;
                foreach ($subjects as $key => $subject) {
                    if($student->results($subject->id)['status'] === 'Dispensado' || $student->results($subject->id)['status'] === 'Aprovado'){
                        array_push($data, [
                            'id'              => $subject->id,
                            'media' => $student->results($subject->id),
                            'school'   => $subject->course->university,
                            'course'   => $subject->course->name,
                            'subject'  => $subject->name
                        ]);
                    }
                }
                $response = ['subjects' => $data];
                break;
            default:
                
                break;
        }
        return response()->json($response, Response::HTTP_OK);
    }  
    public function generateCertificate(Request $request)
    {
        $student = Auth::user();
        if(isset($request->subject_id)){    
            $subject = $student->subjects()->where('subject_id',$request->subject_id)->first();
            
            $cerficate = new User();
            $cerficate->date = $student->results($subject->id)['date'];
            $cerficate->media = $student->results($subject->id)['media'];
            $cerficate->status = $student->results($subject->id)['status'];
            $cerficate->schoolName = $subject->course->university->name;
            $cerficate->schoolPhoto = $subject->course->university->photo;
            $cerficate->schoolAcronym = $subject->course->university->acronym;
            $cerficate->course = $subject->course->name;
            $cerficate->subject = $subject->name;
            $cerficate->student = $student->name.' '.$student->surname;
            
            $pdf = PDF::loadView('dashboard.certificates.subject', compact('cerficate'));
            $pdfData = $pdf->output();
    
            return response()->json(['certificate' => base64_encode($pdfData)]);
        }
        if(isset($request->course_id)){    
            $course = $student->courses()->where('course_id',$request->course_id)->first();
            $data = [];
            $aproved = true;
            $sum = 0;
            $date = '';
            $total = count($course->subjects);
            foreach ($course->subjects as $key => $subject) {
                if(!($student->results($subject->id)['status'] === 'Dispensado' || $student->results($subject->id)['status'] === 'Aprovado')){
                    $aproved = false;
                    break;
                }else{
                    $sum += $student->results($subject->id)['media'];
                    $date = $student->results($subject->id)['date'];
                }
            }
            if($aproved){
                $cerficate = new User();
                $cerficate->date = $date;
                $cerficate->media = $sum/$total;
                $cerficate->course = $course->name;
                $cerficate->schoolName = $course->university->name;
                $cerficate->schoolPhoto = $course->university->photo;
                $cerficate->schoolAcronym = $course->university->acronym;
                $cerficate->student = $student->name.' '.$student->surname;
                
                $pdf = PDF::loadView('dashboard.certificates.course', compact('cerficate'));
                $pdfData = $pdf->output();
        
                return response()->json(['certificate' => base64_encode($pdfData)]);
            }
            $response = ['courses' => $data];
            
            
        }
    }  
}