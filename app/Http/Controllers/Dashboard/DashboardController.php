<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\University;
use App\Models\Lession;
use App\Models\Course;
use App\Models\User;

class DashboardController extends Controller
{
    public function index ()
    {
        $user = Auth::user();
        if($user->hasRole('Administrador') || $user->hasRole('Operador')){
            $universities = University::all();
            $courses = Course::all();
            $lessions = Lession::all();
            $users = User::all();
            $students = [];$schools = [];$operators = [];
            foreach ($users as $key => $user) {
                if ($user->hasRole('Estudante')) {
                    array_push($students,$user);
                }elseif ($user->hasRole('Escola')) {
                    array_push($schools,$user);
                }elseif ($user->hasRole('Operador')) {
                    array_push($operators,$user);
                }
                
            }
            return view('dashboard.index', compact('universities','courses','lessions','students','schools','operators'));
        }
        
        if($user->hasRole('Escola')){
            $students = [];
            foreach ($user->universities as $university) {
                foreach ($university->courses as $course) {
                    foreach ($course->users as $student) {
                        if(!in_array($student, $students)){
                            array_push($students, $student);
                        }
                    }
                }
            }
            return view('dashboard.schools.school', compact('students'));            
        }
    }

    public function results(string $id){

    }

    public function certificates(string $id)
    {
        // Encontrar o estudante
        $student = User::find($id);
        
        $courses = [];
        $coursesFind = $student->courses;
        
        foreach ($coursesFind as $key => $course) {
            $aproved = true;
            $sum = 0;
            $total = count($course->subjects);
            foreach ($course->subjects as $key => $subject) {
                if(!($student->results($subject->id)['status'] === 'Dispensado' || $student->results($subject->id)['status'] === 'Aprovado')){
                    $aproved = false;
                    break;
                }else{
                    $sum += $student->results($subject->id)['media'];
                }
            }
            if($aproved){
                array_push($courses, [
                    'id'       => $course->id,
                    'media'    => $sum/$total,
                    'course'   => $course->name
                ]);
            }
        }

        $subjects = [];
        $subjectsFind = $student->subjects;
        foreach ($subjectsFind as $key => $subject) {
            if($student->results($subject->id)['status'] === 'Dispensado' || $student->results($subject->id)['status'] === 'Aprovado'){
                array_push($subjects, [
                    'id'              => $subject->id,
                    'media' => $student->results($subject->id)['media'],
                    'course'   => $subject->course->name,
                    'subject'  => $subject->name
                ]);
            }
        }
        
        return view('dashboard.schools.results', compact('courses','subjects','student'));
    } 

    public function generateCertificate(Request $request)
    {
        $student = User::find($request->student_id);
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
            
            $pdf=Pdf::loadView('dashboard.certificates.subject', compact('cerficate'));
            $pdf->save(public_path('assets/certificado.pdf'));
            $path = public_path('assets/certificado.pdf');
            $fileName = 'certificado.pdf';
            return response()->download($path, $fileName)->deleteFileAfterSend(true);
        }
        
        if(isset($request->course_id)){
            $course = $student->courses()->where('course_id',$request->course_id)->first();

            $cerficate = new User();
            $cerficate->date = $student->results($course->id)['date'];
            $cerficate->media = $student->results($course->id)['media'];
            $cerficate->status = $student->results($course->id)['status'];

            $cerficate->schoolName = $course->course->university->name;
            $cerficate->schoolPhoto = $course->course->university->photo;
            $cerficate->schoolAcronym = $course->course->university->acronym;
            $cerficate->course = $course->course->name;
            $cerficate->course = $course->name;
            $cerficate->student = $student->name.' '.$student->surname;
            
            $pdf=Pdf::loadView('dashboard.certificates.subject', compact('cerficate'));
            $pdf->save(public_path('assets/certificado.pdf'));
            $path = public_path('assets/certificado.pdf');
            $fileName = 'certificado.pdf';            
            return response()->download($path, $fileName)->deleteFileAfterSend(true);
        }
    }

}
