<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Models\Requirement;
use App\Models\Course;
use App\Models\User;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::select('id','name','degree','duration','description','skills_description','university_id')
                    ->with(['university','requirements','skills'])
                    ->get();
        return response()->json(['courses' => $courses], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'degree' => 'required|string',
            'duration' => 'required|string',
            'description' => 'required|string',
            'skills_description' => 'required|string',
            'university' => 'required'
        ]);
        
        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
        
        // Cria um novo Curso
        $course = new Course();
        $course->name = $request->name;
        $course->degree = $request->degree;
        $course->duration = $request->duration;
        $course->description = $request->description;
        $course->skills_description = $request->skills_description;
        $course->university_id = $request->university;
        $course->save();
        
        // Retorna a resposta de sucesso
        return response()->json(['course' => $course], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Encontrar o curso
        $course = Course::find($id);
        
        // Retorna a resposta de erro
        if (!$course) {
            return response()->json(['message' => 'Curso não encontrado'], Response::HTTP_NOT_FOUND);
        }
        
        $response = [
            'id'                 => $course->id,
            'name'               => $course->name,
            'degree'             => $course->degree,
            'duration'           => $course->duration,
            'description'        => $course->description,
            'skills_description' => $course->skills_description,
            'requirements'       => $course->requirements,
            'subjects'           => $course->subjects,
            'skills'             => $course->skills, 
            'university'         => $course->university,
        ];
        
        // Retorna a resposta de sucesso
        return response()->json(['course' => $response], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Encontrar o curso
        $course = Course::find($id);
        
        // Retorna a resposta de erro
        if (!$course) {
            return response()->json(['message' => 'Curso não encontrado'], Response::HTTP_NOT_FOUND);
        }
        
        // Atualiza os dados do curso
        $course->update($request->all());
        
        // Retorna a resposta de sucesso
        return response()->json(['course' => $course], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Add Requirements of Course
     */
    public function requirements (Request $request, string $id)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'requirements' => 'required',
        ]);
        
        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
        // Encontrar o curso
        $course = Course::find($id);
        
        // Retorna a resposta de erro
        if (!$course) {
            return response()->json(['message' => 'Curso não encontrado'], Response::HTTP_NOT_FOUND);
        }
        
        // Salvar novos Requisitos do curso
        $course->requirements()->createMany($request->requirements);

        // Retorna a resposta de sucesso
        return response()->json(['requirements' => $course->requirements], Response::HTTP_CREATED);
    }

    /**
     * Add skills of Course
     */
    public function skills (Request $request, string $id)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'skills' => 'required',
        ]);
        
        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Encontrar o curso
        $course = Course::find($id);
        
        // Retorna a resposta de erro
        if (!$course) {
            return response()->json(['message' => 'Curso não encontrado'], Response::HTTP_NOT_FOUND);
        }
        
        // Salvar novas Competencias do curso
        $course->skills()->createMany($request->skills);

        // Retorna a resposta de sucesso
        return response()->json(['skills' => $course->skills], Response::HTTP_CREATED);
    }

    /**
     * Attach Student to Course
     */
    public function student(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|integer',
            'student_id' => 'required|integer',
        ]);
        
        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Encontrar o curso
        $course = Course::find($request->course_id);
        
        // Retorna a resposta de erro
        if (!$course) {
            return response()->json(['message' => 'Curso não encontrado'], Response::HTTP_NOT_FOUND);
        }

        // Encontrar o estudante
        $student = User::find($request->student_id);
        
        // Retorna a resposta de erro
        if (!$student) {
            return response()->json(['message' => 'Estudante não encontrado'], Response::HTTP_NOT_FOUND);
        }
        
        // Relacionar o estudante e curso
        $course->users()->attach($student->id);

        return response()->json(['courses' => $student->courses], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function showSubjects(string $id)
    {
        // Encontrar o curso
        $course = Course::find($id);
        
        // Retorna a resposta de erro
        if (!$course) {
            return response()->json(['message' => 'Curso não encontrado'], Response::HTTP_NOT_FOUND);
        }
        
        // Retorna a resposta de sucesso
        return response()->json(['subjects' => $course->subjects], Response::HTTP_OK);
    }
}
