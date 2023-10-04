<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\User;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operators = Role::select('id','name')
        ->with(['users'])
        ->where('roles.name','Operador')
        ->get();
        return view('dashboard.operator.index', ['operators' => $operators[0]->users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.operator.create');
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
            $operator = new User();
            $operator->name = $request->name;
            $operator->surname = $request->surname;
            $operator->email = $request->email;
            $operator->password = Hash::make('operator');
            $operator->save();

            // Conceder papel de Operador
            $operator->roles()->attach(2);

            Toastr::success('Operador adicionado com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar operador  :','Error');
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
        // Encontra o operator
        $operator = User::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$operator) {
            Toastr::error('Operador não encontrado :','Error');
            return redirect()->back();
        }
        return view('dashboard.operator.edit',['operator' => $operator]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Encontra o Operador
        $operator = User::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$operator) {
            Toastr::error('Operador não encontrado :','Error');
            return redirect()->back();
        }
        // Atualiza os dados do Operador
        $operator->update($request->all());
        
        Toastr::success('Operador actualizado com sucesso :','Success');
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
