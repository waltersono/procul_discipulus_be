<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\Models\University;
use App\Models\Role;
use App\Models\User;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = Role::select('id','name')
        ->with(['users'])
        ->where('roles.name','Escola')
        ->get();
        return view('dashboard.school.index', ['schools' => $schools[0]->users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $universities = University::select('id','name')->get();
        return view('dashboard.school.create', compact('universities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        DB::beginTransaction();
        try {
            $school = new User();
            $school->name = $request->name;
            $school->surname = $request->surname;
            $school->email = $request->email;
            $school->password = Hash::make('school');
            $school->phone = $request->phone;
            $school->save();

            // Conceder papel de Escola
            $school->roles()->attach(3);
            $school->schools()->attach($request->university);

            Toastr::success('Operador de escola adicionado com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar Operador de escola  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Encontra a escola
        $school = User::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$school) {
            Toastr::error('Operador não encontrado :','Error');
            return redirect()->back();
        }
        $universities = University::select('id','name')->get();
        return view('dashboard.school.edit',['school' => $school,'universities'=>$universities]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Encontra o Operador da Escola
        $school = User::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$school) {
            Toastr::error('Operador da Escola não encontrado :','Error');
            return redirect()->back();
        }
        // Atualiza os dados do Operador da Escola
        $school->update($request->all());
        
        Toastr::success('Operador da Escola actualizado com sucesso :','Success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
