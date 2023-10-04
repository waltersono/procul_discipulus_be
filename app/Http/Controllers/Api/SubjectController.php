<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Models\Thematicunit;
use App\Models\Subject;
use App\Models\Lession;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::select('id','name','level','semester','type','time','credits','course_id')
                    ->with(['course','objectives','thematics.lessions'])
                    ->get();
        // Retorna a resposta de sucesso
        return response()->json(['subjects' => $subjects], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'level' => 'required|integer',
            'semester' => 'required|integer',
            'type' => 'required|string',
            'time' => 'required|integer',
            'credits' => 'integer',
            'course' => 'required'
        ]);

        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Cria uma nova Disciplina
        $subject = new Subject();
        $subject->name = $request->name;
        $subject->level = $request->level;
        $subject->semester = $request->semester;
        $subject->type = $request->type;
        $subject->time = $request->time;
        $subject->credits = $request->credits;
        $subject->course_id = $request->course;
        $subject->save();

        // Retorna a resposta de sucesso
        return response()->json(['subject' => $subject], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Encontrar a disciplina
        $subject = Subject::find($id);

        // Retorna a resposta de erro
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], Response::HTTP_NOT_FOUND);
        }

        $response = [
            'id'         => $subject->id,
            'name'       => $subject->name,
            'level'      => $subject->level,
            'semester'   => $subject->semester,
            'type'       => $subject->type,
            'time'       => $subject->time,
            'credits'    => $subject->credits,
            'thematics'  => $subject->thematics,
            'objectives' => $subject->objectives,
            'course'     => $subject->course,
            'university' => $subject->course->university->photo,
        ];
        // Retorna a resposta de sucesso
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Add Objectives of Subject
     */
    public function objectives (Request $request, string $id)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'objectives' => 'required',
        ]);

        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
        // Encontrar a disciplina
        $subject = Subject::find($id);

        // Retorna a resposta de erro
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Salvar novos Requisitos da disciplina
        $subject->objectives()->createMany($request->objectives);

        // Retorna a resposta de sucesso
        return response()->json(['objectives' => $subject->objectives], Response::HTTP_CREATED);
    }

    /**
     * Add Thematic Units of Subject
     */
    public function thematics (Request $request, string $id)
    {
        // Validação dos dados
         $validator = Validator::make($request->all(), [
            'thematics' => 'required',
        ]);

        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Encontrar a disciplina
        $subject = Subject::find($id);

        // Retorna a resposta de erro
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Salvar novos Requisitos da disciplina
        $subject->thematics()->createMany($request->thematics);

        // Retorna a resposta de sucesso
        return response()->json(['thematics' => $subject->thematics], Response::HTTP_CREATED);
    }

    /**
     * Show Thematics of Subject
     */
    public function showThematics (string $id)
    {
        // Encontrar a disciplina
        $subject = Subject::find($id);

        // Retorna a resposta de erro
        if (!$subject) {
            return response()->json(['message' => 'Unidade temática não encontrada'], Response::HTTP_NOT_FOUND);
        }
        // Retorna a resposta de sucesso
        return response()->json(['lessions' => $subject->thematics], Response::HTTP_OK);
    }

    /**
     * Add Lessions of Thematic Unit
     */
    public function lessions (Request $request, string $id)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'lessions' => 'required',
        ]);

        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Encontrar a unidade temática
        $thematic = Thematicunit::find($id);

        // Retorna a resposta de erro
        if (!$thematic) {
            return response()->json(['message' => 'Unidade temática não encontrada'], Response::HTTP_NOT_FOUND);
        }
        foreach ($request->lessions as $key => $lession) {
            $new = Lession::create([
                'name' => $lession['name'],
                'subject_id' => $thematic->subject_id,
                'thematicunit_id' => $thematic->id,
            ]);
        }

        // Retorna a resposta de sucesso
        return response()->json(['lessions' => $thematic->lessions], Response::HTTP_CREATED);
    }

    /**
     * Show Lessions of Subject
     */
    public function showLessions (string $id)
    {
        // Encontrar a disciplina
        $subject = Subject::find($id);

        // Retorna a resposta de erro
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Encontrar as unidade temática da disciplina
        $thematics = $subject->thematics;

        $lessions = [];
        foreach ($thematics as $key => $thematic) {
            array_push($lessions, [
                'thematic_id' => $thematic->id,
                'thematic_name' => $thematic->name,
                'lessions' => $thematic->lessions
            ]);
        }

        // Retorna a resposta de sucesso
        return response()->json(['lessions' => $lessions], Response::HTTP_OK);
    }

    public function showMaterial(string $id)
    {
        // Encontrar a disciplina
        $subject = Subject::find($id);
        
        // Retorna a resposta de erro
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], Response::HTTP_NOT_FOUND);
        }
        
        // Retorna a resposta de sucesso
        return response()->json(['materials' => $subject->materials], Response::HTTP_OK);
    }

}
