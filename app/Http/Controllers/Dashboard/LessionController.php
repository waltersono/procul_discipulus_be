<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\Models\Thematicunit;
use App\Models\Subject;
use App\Models\Summary;
use App\Models\Lession;
use App\Models\User;

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
        $request->validate([
            'name' => 'required',
            // 'file_path' => 'required',
            'subject_id' => 'required',
            // 'type' => 'required',
        ]);

        // Encontrar a disciplina
        $subject = Subject::find($request->subject_id);

        // Retorna a resposta de erro
        if (!$subject) {
            Toastr::error('Disciplina não encontrada  :','Error');
            return redirect()->back();
        }

        // Encontrar a unidade temática
        // $thematic = Thematicunit::find($request->thematicunit_id);

        // Retorna a resposta de erro
        // if (!$thematic) {
        //     Toastr::error('Unidade temática não encontrada  :','Error');
        //     return redirect()->back();
        // }
        DB::beginTransaction();
        try {
            // Cria uma nova Aula
            $lession = new Lession;
            $lession->name = $request->name;
            $lession->subject_id = $request->subject_id;
            $lession->type = 'Normal';
            $lession->save();
            // Salva o ficheiro da aula
            if ($request->hasFile('file_path')) {
                $file = $request->file('file_path');
                $filename = $file->getClientOriginalName();
                // Define o caminho do ficheiro
                $path = $file->store('public/uploads/lessions/'.$lession->id,$filename);
                $lession->file_path = $path;
            }
            $lession->update();
            // Retorna a resposta de sucesso
            Toastr::success('Aula adicionada com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar aula  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        // Encontra a aula
        $lession = Lession::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$lession) {
            Toastr::error('Aula não encontrada :','Error');
            return redirect()->back();
        }
        
        return view('dashboard.subject.lession',compact('lession'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Encontra a disciplina
        $lession = Lession::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$lession) {
            Toastr::error('Aula não encontrada :','Error');
            return redirect()->back();
        }
        $subject = $lession->subject;

        return view('dashboard.subject.show',['lession' => $lession,'subject' => $subject, 'activeTab' => 'bottom-lessions','contentTab' => 'lession']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Encontra a aula
        $lession = Lession::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$lession) {
            Toastr::error('Aula não encontrada :','Error');
            return redirect()->back();
        }
        
        // Actualizar os dados da aula
        DB::beginTransaction();
        try {
            $lession->update($request->all());
            Toastr::success('Aula actualizada com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao actualizar aula :','Error');
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
     * Store a newly created resource in storage.
     */
    public function summary(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'lession_id' => 'required',
        ],[
            'title.required' => 'O campo título é obrigatório.',
            'type.required' => 'O campo tipo de ficheiro é obrigatório.'
        ]);
        // Encontrar a aula
        $lession = Lession::find($request->lession_id);

        // Retorna a resposta de erro
        if (!$lession) {
            Toastr::error('Aula não encontrada  :','Error');
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            // Cria um novo topico
            $summay = new Summary;
            $summay->name = $request->title;
            $summay->type = $request->type;
            $summay->lession_id = $request->lession_id;
            $summay->save();
            // Salva o ficheiro da aula
            if ($request->hasFile('file_path')) {
                $file = $request->file('file_path');
                // $filename = $file->getClientOriginalName();
                // Define o caminho do ficheiro
                $path = $file->store('public/uploads/lessions/'.$lession->id.'/summary');
                $summay->file_path = $path;
            }else{
                $summay->file_path = $request->link;
            }
            $summay->update();
            // Retorna a resposta de sucesso
            Toastr::success('Tópico adicionada com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar tópico  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }    
}
