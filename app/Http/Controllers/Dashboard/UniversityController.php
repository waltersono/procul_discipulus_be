<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $universities = University::select('id','name','acronym','photo')->get();
        return view('dashboard.university.index', compact('universities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.university.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|unique:universities|max:255',
            'acronym' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
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
            Toastr::success('Escola adicionada com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar escola  :','Error');
            DB::rollback();
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $university)
    {
        // Encontra a universidade
        $school = University::find($university);

        // Retorna a resposta de erro se não encontrar
        if (!$school) {
            Toastr::error('Escola não encontrada :','Error');
            return redirect()->back();
        }
        return view('dashboard.university.show',['university' => $school]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $university)
    {
        // Encontra a universidade
        $school = University::find($university);

        // Retorna a resposta de erro se não encontrar
        if (!$school) {
            Toastr::error('Escola não encontrada :','Error');
            return redirect()->back();
        }
        return view('dashboard.university.edit',['university' => $school]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $university)
    {
        // Encontra a universidade
        $school = University::find($university);

        // Retorna a resposta de erro se não encontrar
        if (!$school) {
            Toastr::error('Escola não encontrada :','Error');
            return redirect()->back();
        }
        // Atualiza os dados do Univerisidade
        $school->update($request->all());
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = $photo->getClientOriginalName();
            // Define o caminho da foto no registro do usuário
            $path = $request->file('photo')->storeAs('public/uploads/university/photo',$filename);
            $school->photo = 'storage/uploads/university/photo/'.$filename;
            $school->update();
        }
        Toastr::success('Escola actualizada com sucesso :','Success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(University $university)
    {
        //
    }
}
