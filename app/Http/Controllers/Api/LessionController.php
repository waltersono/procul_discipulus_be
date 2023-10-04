<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Models\Thematicunit;
use App\Models\Subject;
use App\Models\Lession;
use App\Models\Question;

class LessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'file_path' => 'required',
            'subject_id' => 'required',
            'thematicunit_id' => 'required',
        ]);
        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        // Encontrar a disciplina
        $subject = Subject::find($request->subject_id);

        // Retorna a resposta de erro
        if (!$subject) {
            return response()->json(['message' => 'Disciplina não encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Encontrar a unidade temática
        $thematic = Thematicunit::find($request->thematicunit_id);

        // Retorna a resposta de erro
        if (!$thematic) {
            return response()->json(['message' => 'Unidade temática não encontrada'], Response::HTTP_NOT_FOUND);
        }
        
        // Cria uma nova Aula
        $lession = new Lession;
        $lession->name = $request->name;
        $lession->subject_id = $request->subject_id;
        $lession->thematicunit_id = $request->thematicunit_id;
        $lession->save();
        // Salva o ficheiro da aula
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = $file->getClientOriginalName();
            // Define o caminho do ficheiro
            $path = $file->storeAs('public/uploads/lessions/'.$lession->id,$filename);
            $lession->file_path = 'storage/uploads/lessions/'.$lession->id.'/'.$filename;
        }
        $lession->update();
        // Retorna a resposta de sucesso
        return response()->json(['lession' => $lession], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Encontrar o curso
        $lession = Lession::find($id);
        
        // Retorna a resposta de erro
        if (!$lession) {
            return response()->json(['message' => 'Aula não encontrada'], Response::HTTP_NOT_FOUND);
        }
        
        $response = [
            'id'            => $lession->id,
            'name'          => $lession->name,
            'file_path'     => $lession->file_path,
            'thematicunit'  => $lession->thematic,
            'subject'       => $lession->subject,
        ];
        
        // Retorna a resposta de sucesso
        return response()->json(['lession' => $lession], Response::HTTP_OK);
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
}
