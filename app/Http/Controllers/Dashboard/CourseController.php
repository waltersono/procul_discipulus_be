<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\Models\Requirement;
use App\Models\University;
use App\Models\Subject;
use App\Models\Role;
use App\Models\User;
use App\Models\Skill;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();
        return view('dashboard.course.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $universities = University::select('id','name')->get();
        return view('dashboard.course.create', compact('universities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'degree' => 'required|string',
            'duration' => 'required|string',
            'description' => 'required',
            'skills_description' => 'required',
            'university' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // Cria um novo Curso
            $course = new Course();
            $course->name = $request->name;
            $course->degree = $request->degree;
            $course->duration = $request->duration;
            $course->description = $request->description;
            $course->university_id = $request->university;
            $course->skills_description = $request->skills_description;
            $course->save();
            Toastr::success('Curso adicionado com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar curso  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $course)
    {
        // Encontra a curso
        $found = Course::find($course);

        // Retorna a resposta de erro se não encontrar
        if (!$found) {
            Toastr::error('Curso não encontrado :','Error');
            return redirect()->back();
        }
        $users = Role::select('id','name')
        ->with(['users'])
        ->where('roles.name','Estudante')
        ->get();
        
        return view('dashboard.course.show',['course' => $found,'users' => $users[0]->users]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $course)
    {
        // Encontra o curso
        $found = Course::find($course);

        // Retorna a resposta de erro se não encontrar
        if (!$found) {
            Toastr::error('Curso não encontrado :','Error');
            return redirect()->back();
        }
        $universities = University::select('id','name')->get();
        return view('dashboard.course.edit',['course' => $found,'universities' => $universities]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $course)
    {
        // Encontra a universidade
        $found = Course::find($course);

        // Retorna a resposta de erro se não encontrar
        if (!$found) {
            Toastr::error('Curso não encontrado :','Error');
            return redirect()->back();
        }

        // Atualiza os dados do Univerisidade
        DB::beginTransaction();
        try {
            $found->update($request->all());
            Toastr::success('Curso actualizado com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao actualizar curso  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Attach Student to Course
     */
    public function student(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'course_id' => 'required|integer',
            'student_id' => 'required|integer',
            'subject_id' => 'required|integer',
        ]);

        // Encontrar o curso
        $course = Course::find($request->course_id);
        
        // Retorna a resposta de erro
        if (!$course) {
            Toastr::error('Curso não encontrado  :','Error');
        }

        // Encontrar o estudante
        $student = User::find($request->student_id);
        
        // Retorna a resposta de erro
        if (!$student) {
            Toastr::error('Estudante não encontrado  :','Error');
        }

        // Encontrar a disciplina
        $subject = Subject::find($request->subject_id);
        
        // Retorna a resposta de erro
        if (!$subject) {
            Toastr::error('Disciplina não encontrada  :','Error');
        }

        DB::beginTransaction();
        try {
            // Relacionar o estudante e disciplina
            if($subject->students()->where('user_id', $student->id)->exists()){
                Toastr::error($student->name.' '.$student->surname.' já faz a '.$subject->name.':','Error');
                return redirect()->back()->withInput($request->input());
            }else{
                $student->subjects()->attach($subject->id);
                $student->testes()->attach($subject->testes);
                if(!($course->users()->where('user_id', $student->id)->exists())){
                    $course->users()->attach($student->id);
                }
                Toastr::success($student->name.' '.$student->surname.' associonado a '.$subject->name.' com sucesso :','Success');
                DB::commit();
                return redirect()->back();
            }
        } catch(\Exception $e) {
            Toastr::error('Falha ao associar o estudante a disciplina :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }

    /**
     * Attach requirement to Course
     */
    public function requirement(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'course_id' => 'required|integer',
            'requirement' => 'required',
        ]);

        // Encontrar o curso
        $course = Course::find($request->course_id);
        
        // Retorna a resposta de erro
        if (!$course) {
            Toastr::error('Curso não encontrado  :','Error');
        }
        
        DB::beginTransaction();
        try {
            // Cria um novo requisito
            $requirement = Requirement::firstOrCreate([
                'course_id' => $request->course_id,
                'name' => $request->requirement
            ]);
            
            Toastr::success('Requisito de acesso adicionado com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar requisito de acesso  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }

    /**
     * Attach skill to Course
     */
    public function skill(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'course_id' => 'required|integer',
            'skill' => 'required',
        ]);

        // Encontrar o curso
        $course = Course::find($request->course_id);
        
        // Retorna a resposta de erro
        if (!$course) {
            Toastr::error('Curso não encontrado  :','Error');
        }
        
        DB::beginTransaction();
        try {
            // Cria um novo peril ocupacional
            $skill = Skill::firstOrCreate([
                'course_id' => $request->course_id,
                'name' => $request->skill
            ]);
            
            Toastr::success('Requisito de acesso adicionado com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar requisito de acesso  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }
    
    public function addStudent()
    {
        
        $courses = [];
        $user = Auth::user();
        foreach ($user->universities as $university) {
            foreach ($university->courses as $course) {
                if(!in_array($course, $courses)){
                    array_push($courses, $course);
                }
            }
        }
        $users = Role::select('id','name')
        ->with(['users'])
        ->where('roles.name','Estudante')
        ->get();
        $students = $users[0]->users;
        return view('dashboard.schools.addStudent', compact('students','courses'));
    }

    public function studentStatus(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'subject_id' => 'required|integer',
            'student_id' => 'required',
        ],[
            'subject_id.required' => 'O campo disciplina é obrigatório.',
            'student_id.required' => 'O campo estudante é obrigatório.'
        ]);

        // Encontrar a disciplina
        $subject = Subject::find($request->subject_id);       
        // Retorna a resposta de erro
        if (!$subject) {
            Toastr::error('Disciplina não encontrada  :','Error');
        }     
        
        // Encontrar o estudante
        $student = User::find($request->student_id);
        // Retorna a resposta de erro
        if (!$student) {
            Toastr::error('Estudante não encontrado  :','Error');
        }
        
        try {
            $status = $student->subjects()->where('subject_id',$request->subject_id)->first();
            if($status->pivot->status){
                $student->subjects()->updateExistingPivot($request->subject_id, ['status' => false]);
                Toastr::success('Disciplina bloqueada com sucesso :','Success');
            }else{
                $student->subjects()->updateExistingPivot($request->subject_id, ['status' => true]);
                Toastr::success('Disciplina desbloqueada com sucesso :','Success');
            }
            
        } catch(\Exception $e) {
            Toastr::error('Falha ao realizar esta operação :','Error');
            
        }
        return redirect()->back();
    }

    public function subjects(string $course_id)
    {
        // Encontrar o curso
        $course = Course::find($course_id);       
        // Retorna a resposta de erro
        if (!$course) {
            Toastr::error('Curso não encontrado  :','Error');
        } 
        return response()->json($course->subjects);
    }
    
}
