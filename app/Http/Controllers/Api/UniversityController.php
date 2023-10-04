<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Models\University;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $universities = University::select('id','name','acronym','photo')->get();
        return response()->json(['universities' => $universities], Response::HTTP_OK);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:universities|max:255',
            'acronym' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
        // Cria uma nova Universidade
        $university = new University;
        $university->name = $request->name;
        $university->acronym = $request->acronym;
        // Salva a foto de perfil
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = $photo->getClientOriginalName();
            // Define o caminho da foto no registro do usuário
            $path = $request->file('photo')->storeAs('public/uploads/university/photo',$filename);
            $university->photo = 'storage/uploads/university/photo/'.$filename;
        }
        $university->save();
        // Retorna a resposta de sucesso
        return response()->json(['university' => $university], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Encontra a universidade
        $university = University::find($id);
        
        // Retorna a resposta de erro
        if (!$university) {
            return response()->json(['message' => 'Universidade não encontrada'], Response::HTTP_NOT_FOUND);
        }
        
        // Retorna a resposta de sucesso
        return response()->json(['university' => $university], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Encontra a universidade
        $university = University::find($id);

        // Retorna a resposta de erro
        if (!$university) {
            return response()->json(['message' => 'Univerisidade não encontrada'], Response::HTTP_NOT_FOUND);
        }

        // Atualiza os dados do Univerisidade
        $university->update($request->all());

        // Retorna a resposta de sucesso
        return response()->json(['University' => $university], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Encontra a universidade
        $university = University::find($id);

        // Retorna a resposta de erro
        if (!$university) {
            return response()->json(['message' => 'Univerisidade não encontrado'], Response::HTTP_NOT_FOUND);
        }

        // Exclui a universidade
        $university->delete();

        // Retorna a resposta de sucesso
        return response()->json(['message' => 'Univerisidade excluída com sucesso'], Response::HTTP_OK);
    }

}
