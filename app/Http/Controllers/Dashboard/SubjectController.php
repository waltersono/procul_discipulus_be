<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\Models\Thematicunit;
use App\Models\Objective;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Lession;
use App\Models\Question;
use App\Models\Material;
use App\Models\Inquiry;
use App\Models\Teste;
use App\Models\Role;

class SubjectController extends Controller
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
            'name' => 'required|string|max:255',
            'level' => 'required|integer',
            'semester' => 'required|integer',
            'type' => 'required|string',
            'time' => 'required|integer',
            'credits' => 'integer',
            'course' => 'required'
        ]);

        // Encontrar o curso
        $course = Course::find($request->course);
        
        // Retorna a resposta de erro
        if (!$course) {
            Toastr::error('Curso não encontrado  :','Error');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
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
            Toastr::success('Disciplina adicionada com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar disciplina  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Encontra a disciplina
        $subject = Subject::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$subject) {
            Toastr::error('Disciplina não encontrada :','Error');
            return redirect()->back();
        }
        
        return view('dashboard.subject.show',['subject' => $subject, 'activeTab' => 'bottom-general','contentTab' => 'lession']);
    }

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Encontra a disciplina
        $subject = Subject::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$subject) {
            Toastr::error('Disciplina não encontrada :','Error');
            return redirect()->back();
        }
        $course = $subject->course;
        $users = Role::select('id','name')
        ->with(['users'])
        ->where('roles.name','Estudante')
        ->get();

        return view('dashboard.course.show',['subject_edit' => $subject,'course' => $course,'users' => $users[0]->users]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Encontra a disciplina
        $subject = Subject::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$subject) {
            Toastr::error('Disciplina não encontrada :','Error');
            return redirect()->back();
        }

        // Atualiza os dados da disciplina
        DB::beginTransaction();
        try {
            $subject->update($request->all());
            Toastr::success('Disciplina actualizada com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao actualizar disciplina  :','Error');
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
     * Attach objective to Course
     */
    public function objective(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'subject_id' => 'required|integer',
            'objective' => 'required',
        ]);

        // Encontrar o disciplina
        $subject = Subject::find($request->subject_id);
        
        // Retorna a resposta de erro
        if (!$subject) {
            Toastr::error('Disciplina não encontrada  :','Error');
        }
        
        DB::beginTransaction();
        try {
            // Cria um novo peril ocupacional
            $objective = Objective::firstOrCreate([
                'subject_id' => $request->subject_id,
                'name' => $request->objective
            ]);
            
            Toastr::success('Objectivo adicionado com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar objectivo  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }
    
    /**
     * Attach thematic to Course
     */
    public function thematic(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'subject_id' => 'required|integer',
            'thematic' => 'required',
            'time' => 'required',
        ]);
        // Encontrar o disciplina
        $subject = Subject::find($request->subject_id);
        // Retorna a resposta de erro
        if (!$subject) {
            Toastr::error('Disciplina não encontrada  :','Error');
        }
        DB::beginTransaction();
        try {
            // Cria uma nova unidade tematica
            $thematic = Thematicunit::firstOrCreate([
                'subject_id' => $request->subject_id,
                'name' => $request->thematic,
                'time' => $request->time
            ]);
            Toastr::success('Unidade temática adicionada com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar unidade temática  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }

    /**
     * Add question to Subject
     */
    public function question(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'lession_id'          => 'required|integer',
            'question'            => 'required',
            'optionA'             => 'required',
            'optionB'             => 'required',
            'optionC'             => 'required',
            'optionD'             => 'required',
            'alterativa_correcta' => 'required',
        ]);
        // Encontrar o disciplina
        $lession = Lession::find($request->lession_id);
        // Retorna a resposta de erro
        if (!$lession) {
            Toastr::error('Aula não encontrada  :','Error');
        }
        $question = Question::firstOrCreate([
            'lession_id' => $request->lession_id,
            'subject_id' => $lession->subject_id,
            'question' => $request->question,
            'optionA' => $request->optionA,
            'optionB' => $request->optionB,
            'optionC' => $request->optionC,
            'optionD' => $request->optionD,
            'correct' => $request->alterativa_correcta
        ]);
        DB::beginTransaction();
        try {
            // Cria uma nova questão
            Toastr::success('Questão adicionada com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar questão  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }
        
    public function material(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'subject_id' => 'required',
        ],[
            'title.required' => 'O campo título é obrigatório.',
            'type.required' => 'O campo tipo de ficheiro é obrigatório.'
        ]);

        // Encontrar a disciplina
        $subject = Subject::find($request->subject_id);

        // Retorna a resposta de erro
        if (!$subject) {
            Toastr::error('Disciplina não encontrada  :','Error');
            return redirect()->back();
        }
        
        try {
            DB::beginTransaction();
            // Cria um novo topico
            $material = new Material;
            $material->name = $request->title;
            $material->type = $request->type;
            $material->subject_id = $request->subject_id;
            $material->save();
            // Salva o ficheiro da aula
            if ($request->hasFile('file_path')) {
                $file = $request->file('file_path');
                $filename = $file->getClientOriginalName();
                // Define o caminho do ficheiro
                $path = $file->storeAs('public/uploads/subjects/'.$request->subject_id.'/material/'.$material->id,$filename);
                $material->file_path = 'storage/uploads/subjects/'.$request->subject_id.'/material/'.$material->id.'/'.$filename;
            }else{
                $material->file_path = $request->link;
            }
            $material->update();
            // Retorna a resposta de sucesso
            Toastr::success('Material adicionado com sucesso :','Success');
            DB::commit();
            return view('dashboard.subject.show',['subject' => $subject, 'activeTab' => 'bottom-material','contentTab' => 'lession']);
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar material  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }   

    public function materialsEdit(string $id)
    {
        // Encontra o material
        $material = Material::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$material) {
            Toastr::error('Material não encontrada :','Error');
            return redirect()->back();
        }        

        $subject = $material->subject;

        return view('dashboard.subject.show',['material' => $material,'subject' => $subject, 'activeTab' => 'bottom-material','contentTab' => 'lession']);
    }    
    
    public function materialsUpdate(Request $request, string $id)
    {
        // Encontra o material
        $material = Material::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$material) {
            Toastr::error('Material não encontrada :','Error');
            return redirect()->back();
        }
        if($request->name){
            $material->name = $request->name;
        }
        if($request->type){
            $material->type = $request->type;
        }
        if($request->subject_id){
            $material->subject_id = $request->subject_id;
        }
        if($request->link){
            $material->file_path = $request->link;
        }
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = $file->getClientOriginalName();
            $path = $file->storeAs('public/uploads/subjects/'.$request->subject_id.'/material/'.$material->id,$filename);
            $material->file_path = 'storage/uploads/subjects/'.$request->subject_id.'/material/'.$material->id.'/'.$filename;
        }
        $material->update();
        DB::beginTransaction();
        $subject = $material->subject;
        try {
            Toastr::success('Material actualizado com sucesso :','Success');
            DB::commit();
            return view('dashboard.subject.show',['material' => $material,'subject' => $subject, 'activeTab' => 'bottom-material','contentTab' => 'lession']);
        } catch(\Exception $e) {
            Toastr::error('Falha ao actualizar material  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }

    public function testes(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'test_name' => 'required',
            'after_lession_id' => 'required',
            'subject_id' => 'required',
        ],[
            'test_name.required' => 'O campo tipo é obrigatório.',
            'after_lession_id.required' => 'O campo aula é obrigatório.',
            'subject_id.required' => 'O campo disciplina é obrigatório.',
        ]);

        // Encontrar a disciplina
        $subject = Subject::find($request->subject_id);

        // Retorna a resposta de erro
        if (!$subject) {
            Toastr::error('Disciplina não encontrada  :','Error');
            return redirect()->back();
        }
        
        try {
            DB::beginTransaction();
            // Cria um novo teste
            $teste = new Teste;
            $teste->name = $request->test_name;
            $teste->after_lession_id = $request->after_lession_id;
            $teste->subject_id = $request->subject_id;
            $teste->save();
            
            // Retorna a resposta de sucesso
            Toastr::success($request->type.' com sucesso :','Success');
            DB::commit();
            return view('dashboard.subject.show',['subject' => $subject, 'activeTab' => 'bottom-lessions','contentTab' => 'testes']);
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar '.$request->type.'  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }
    
    public function testesShow(string $id)
    {
        // Encontra o teste
        $teste = Teste::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$teste) {
            Toastr::error('Avaliação não encontrada :','Error');
            return redirect()->back();
        }

        return view('dashboard.subject.teste',compact('teste'));
    }
    public function testesEdit(string $id)
    {
        // Encontra o teste
        $teste = Teste::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$teste) {
            Toastr::error('Avaliação não encontrada :','Error');
            return redirect()->back();
        }        

        $subject = $teste->subject;

        return view('dashboard.subject.show',['teste' => $teste,'subject' => $subject, 'activeTab' => 'bottom-lessions','contentTab' => 'testes']);
    }

    public function testesUpdate(Request $request, string $id)
    {
        // Encontra o teste
        $teste = Teste::find($id);

        // Retorna a resposta de erro se não encontrar
        if (!$teste) {
            Toastr::error('Avaliação não encontrada :','Error');
            return redirect()->back();
        }
        if($request->test_name){
            $teste->name = $request->test_name;
        }
        if($request->after_lession_id){
            $teste->after_lession_id = $request->after_lession_id;
        }
        if($request->subject_id){
            $teste->subject_id = $request->subject_id;
        }

        $teste->update();

        DB::beginTransaction();
        $subject = $teste->subject;
        try {
            Toastr::success('Teste actualizado com sucesso :','Success');
            DB::commit();
            return view('dashboard.subject.show',['subject' => $subject, 'activeTab' => 'bottom-lessions','contentTab' => 'testes']);
        } catch(\Exception $e) {
            Toastr::error('Falha ao actualizar teste  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }

    /**
     * Add question to Teste
     */
    public function questionsTestes(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'teste_id'          => 'required|integer',
            'question'            => 'required',
            'optionA'             => 'required',
            'optionB'             => 'required',
            'optionC'             => 'required',
            'optionD'             => 'required',
            'alterativa_correcta' => 'required',
        ]);
        // Encontrar o disciplina
        $teste = Teste::find($request->teste_id);
        // Retorna a resposta de erro
        if (!$teste) {
            Toastr::error('Aula não encontrada  :','Error');
        }
        $question = Inquiry::firstOrCreate([
            'teste_id' => $request->teste_id,
            'question' => $request->question,
            'optionA' => $request->optionA,
            'optionB' => $request->optionB,
            'optionC' => $request->optionC,
            'optionD' => $request->optionD,
            'correct' => $request->alterativa_correcta
        ]);
        DB::beginTransaction();
        try {
            // Cria uma nova questão
            Toastr::success('Questão adicionada com sucesso :','Success');
            DB::commit();
            return redirect()->back();
        } catch(\Exception $e) {
            Toastr::error('Falha ao adicionar questão  :','Error');
            DB::rollback();
            return redirect()->back()->withInput($request->input())->withErrors($e->getMessage());
        }
    }
    
}
