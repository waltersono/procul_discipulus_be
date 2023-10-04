<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\User;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Role::select('id','name')
        ->with(['users'])
        ->where('roles.name','Estudante')
        ->get();
        return view('dashboard.student.index', ['students' => $students[0]->users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.student.create');
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
            $student = new User();
            $student->name = $request->name;
            $student->surname = $request->surname;
            $student->email = $request->email;
            $student->password = Hash::make('student');
            $student->phone = $request->phone;
            $student->save();

            // Conceder papel de Estudante
            $student->roles()->attach(4);

            Toastr::success('Estudante adicionado com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar Estudante  :','Error');
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
        // Encontra o estudante
        $student = User::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$student) {
            Toastr::error('Estudante não encontrado :','Error');
            return redirect()->back();
        }
        return view('dashboard.student.edit',['student' => $student]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Encontra o estudante
        $student = User::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$student) {
            Toastr::error('Estudante não encontrado :','Error');
            return redirect()->back();
        }
        // Atualiza os dados do Univerisidade
        $student->update($request->all());
        
        Toastr::success('Estudante actualizado com sucesso :','Success');
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
