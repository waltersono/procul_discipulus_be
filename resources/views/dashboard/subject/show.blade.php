
@extends('layouts.master')

@section('content')

    <style>
        .course-header {
            border: 1px solid #e4e8f7;
            width: 100%;
            cursor: default;
            display: flex;
            justify-content: space-between;
            background-color: white;
        }

        #course {
            display: flex;
            align-items: center;
            margin-bottom: 0;
        }

        #course > .university {
            height: 100px;
            margin: 0 0.5rem;
        }
        
        #course-description {
            display: flex;
            flex-direction: column;
            row-gap: 0.25rem;
            padding: 0.5rem;
            border-left: 1px solid #e4e8f7;
        }

        .name {
            font-weight: 600;
            display: block;
        }
        .degree {
            display: block;
        }
        .duration {
            font-size: 0.75rem;
            display: block;
        }

        .border-header{
            border-bottom: 2px solid black;
        }

        .footer-table {
            border-color: black !important;
            border-bottom: 2px solid black;
        }
    </style>

    <div class="page-wrapper">
        <div class="content container-fluid">            
            <div class='course-header'>
                <div id="course">
                    <img src="{{ URL::to($subject->course->university->photo) }}" alt="Profile" class="university">
                    <div id="course-description" class='text-nowrap'>
                        <span class='name'>{{ $subject->name }}</span>
                        <span class='degree'>Tipo de disciplina: {{ $subject->type }}</span>
                        <span class=''>Carga Horária: {{ $subject->time }}</span>
                        <span class=''>Créditos: {{ $subject->credits }}</span>
                    </div>
                </div>
            </div>
            <br>
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="follow-group">
                        <div class="students-follows text-center">
                            <h5>Aulas</h5>
                            <h4>{{count($subject->lessions)}}</h4>
                        </div>
                        <div class="students-follows text-center">
                            <h5>Estudantes</h5>
                            <h4>{{count($subject->students)}}</h4>
                        </div>
                        <div class="students-follows text-center">
                            <h5>Material</h5>
                            <h4>{{count($subject->materials)}}</h4>
                        </div>
                        <div class="students-follows text-center">
                            <h5>Unidades Tematicas</h5>
                            <h4>{{count($subject->thematics)}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <div class="">
                        <div class="">
                            <ul class="nav nav-tabs nav-tabs-bottom" id="nav-tabs">
                                <li class="nav-item"><a class="nav-link active" href="#bottom-general" data-bs-toggle="tab"  onclick="setActiveTab('bottom-general')">Geral</a></li>
                                <li class="nav-item"><a class="nav-link" href="#bottom-lessions" data-bs-toggle="tab"  onclick="setActiveTab('bottom-lessions')">Aulas</a></li>
                                <li class="nav-item"><a class="nav-link" href="#bottom-material" data-bs-toggle="tab"  onclick="setActiveTab('bottom-material')">Material</a></li>
                            </ul>
                            <div class="tab-content bg-white py-3" id="content-pane">
                                <div class="tab-pane active show" id="bottom-general">
                                    <span class='' style="font-weight: 600">I. Objectivos da Cadeira</span>
                                    <ul class='objectives-list'>
                                        @forelse ($subject->objectives as $item)
                                        <li><i class="fas fa-check"></i> <span>{{$item->name}}</span> </li>
                                        @empty
                                        <li>No objectives found.</li>
                                        @endforelse
                                    </ul>
                                    <span class='' style="font-weight: 600">II. Unidades Temáticas</span>
                                    <div class="table-responsive">
                                        <table class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                            <thead class="border-header">
                                                <tr>
                                                    <th class="">Tema</th>
                                                    <th class="text-center">Horas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($subject->thematics as $thematic )
                                                <tr class="border-bottom">
                                                    <td>{{ $thematic->name }}</td>
                                                    <td class="text-center">{{ $thematic->time }} h</td>
                                                </tr>
                                                @endforeach
                                                <tr class="footer-table"><td colspan="3" class="p-0"></td></tr>
                                                <tr class="footer-table">
                                                    <td class="" style="font-weight: 600; color: black;">Total</td>
                                                    <td class="text-center" style="font-weight: 600; color: black;"> Hrs</td>
                                                    <td class="text-center"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <form class="border" action="{{ route('subjects.objective.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div><span class="px-3 pt-4" style="font-weight: 600; display: block;">Adicionar Objectivos da Disciplina</span></div>
                                                <div class="p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Objectivo <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('objective') is-invalid @enderror" name="objective" placeholder="Informe o Objectivo" value="{{ old('requirement') }}">
                                                                @error('objective')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>                      
                                                        <input type="hidden" name="subject_id" class="" value="{{$subject->id}}">
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="text-align: right;">
                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                    <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <form class="border" action="{{ route('subjects.thematic.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div><span class="px-3 pt-4" style="font-weight: 600; display: block;">Adicionar Unidade Temática</span></div>
                                                <div class="p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Tema <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('thematic') is-invalid @enderror" name="thematic" placeholder="Informe o Tema" value="{{ old('thematic') }}">
                                                                @error('thematic')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div> 
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Horas <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('time') is-invalid @enderror" name="time" placeholder="Informe a Duração" value="{{ old('time') }}">
                                                                @error('time')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="subject_id" class="" value="{{$subject->id}}">
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="text-align: right;">
                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                    <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="bottom-lessions">
                                    <div class="row">
                                        <div class="col-12 col-md-5">
                                            <div class="card-body pt-0">
                                                <ul class="nav nav-tabs nav-tabs-solid nav-justified">
                                                    <li class="nav-item"><a class="nav-link" href="#tabLinkLession" data-bs-toggle="tab">{{isset($lession) ? 'Actualizar':'Adicionar'}} Aula</a></li>
                                                    <li class="nav-item"><a class="nav-link" href="#tabLinkTest" data-bs-toggle="tab">{{isset($teste) ? 'Actualizar':'Adicionar'}} Avaliação</a></li>
                                                </ul>
                                                <div class="tab-content pt-0">
                                                    <div class="tab-pane" id="tabLinkLession">
                                                        @if(isset($lession))
                                                        <form class="border" action="{{ route('lessions.update', $lession->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="p-3">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group local-forms">
                                                                            <label>Tema <span class="login-danger">*</span></label>
                                                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Digite a denominação" value="{{ old('name') ? old('name') : $lession->name }}">
                                                                            @error('name')
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <input type="hidden" name="subject_id" class="" value="{{$subject->id}}">
                                                                    </div>                      
                                                                </div>
                                                            </div>
                                                            <div class="card-footer" style="text-align: right;">
                                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                                                <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                            </div>
                                                        </form>
                                                        @else
                                                        <form class="border" action="{{ route('lessions.store') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="p-3">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group local-forms">
                                                                            <label>Tema <span class="login-danger">*</span></label>
                                                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Digite a denominação" value="{{ old('name') }}">
                                                                            @error('name')
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <input type="hidden" name="subject_id" class="" value="{{$subject->id}}">
                                                                    </div>                      
                                                                </div>
                                                            </div>
                                                            <div class="card-footer" style="text-align: right;">
                                                                <button type="submit" class="btn btn-primary">Salvar</button>
                                                                <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                            </div>
                                                        </form>
                                                        @endif
                                                    </div>
                                                    <div class="tab-pane" id="tabLinkTest">
                                                        @if(isset($teste))
                                                        <form class="border" action="{{ route('subjects.testes.update', $teste->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="p-3">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group local-forms">
                                                                            <label for="type">Tipo <span class="login-danger">*</span></label>
                                                                            <select class="form-control select  @error('test_name') is-invalid @enderror" name="test_name" id="test_name">
                                                                                <option selected disabled>Selecionar o tipo</option>
                                                                                <option value="Teste I" {{ old('test_name') == 'Teste I' || $teste->name == 'Teste I' ? "selected" :""}}>Teste I</option>
                                                                                <option value="Teste II" {{ old('test_name') == 'Teste II' || $teste->name == 'Teste II' ? "selected" :""}}>Teste II</option>
                                                                                <option value="Exame Normal" {{ old('test_name') == 'Exame Normal' || $teste->name == 'Exame Normal' ? "selected" :""}}>Exame Normal</option>
                                                                                <option value="Exame de Recorrência" {{ old('test_name') == 'Exame de Recorrência' || $teste->name == 'Exame de Recorrência' ? "selected" :""}}>Exame de Recorrência</option>
                                                                            </select>
                                                                            @error('test_name')
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group local-forms">
                                                                            <label for="after_lession_id">Depois da aula <span class="login-danger">*</span></label>
                                                                            <select class="form-control select  @error('after_lession_id') is-invalid @enderror" name="after_lession_id" id="after_lession_id">
                                                                                <option selected disabled>Selecionar a aula</option>
                                                                                @foreach ($subject->lessions as $key=> $lession )
                                                                                <option value="{{$lession->id}}" {{ old('after_lession_id') == $lession->id || $lession->id == $teste->after_lession_id ? "selected" :""}}>{{$lession->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('after_lession_id')
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <input type="hidden" name="subject_id" class="" value="{{$subject->id}}">
                                                                    </div>                      
                                                                </div>
                                                            </div>
                                                            <div class="card-footer" style="text-align: right;">
                                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                                                <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                            </div>
                                                        </form>
                                                        @else
                                                        <form class="border" action="{{ route('subjects.testes.store') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="p-3">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group local-forms">
                                                                            <label for="test_name">Tipo <span class="login-danger">*</span></label>
                                                                            <select class="form-control select  @error('test_name') is-invalid @enderror" name="test_name">
                                                                                <option selected disabled>Selecionar o tipo</option>
                                                                                <option value="Teste I" {{ old('test_name') == "Teste I" ? "selected" :""}}>Teste I</option>
                                                                                <option value="Teste II" {{ old('test_name') == "Teste II" ? "selected" :""}}>Teste II</option>
                                                                                <option value="Exame Normal" {{ old('test_name') == "Exame Normal" ? "selected" :""}}>Exame Normal</option>
                                                                                <option value="Exame de Recorrência" {{ old('test_name') == "Exame de Recorrência" ? "selected" :""}}>Exame de Recorrência</option>
                                                                            </select>
                                                                            @error('test_name')
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group local-forms">
                                                                            <label for="after_lession_id">Depois da aula <span class="login-danger">*</span></label>
                                                                            <select class="form-control select  @error('after_lession_id') is-invalid @enderror" name="after_lession_id" id="after_lession_id">
                                                                                <option selected disabled>Selecionar a aula</option>
                                                                                @foreach ($subject->lessions as $key=> $lession )
                                                                                <option value="{{$lession->id}}" {{ old('after_lession_id') == $lession->id ? "selected" :""}}>{{$lession->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('after_lession_id')
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <input type="hidden" name="subject_id" class="" value="{{$subject->id}}">
                                                                    </div>                      
                                                                </div>
                                                            </div>
                                                            <div class="card-footer" style="text-align: right;">
                                                                <button type="submit" class="btn btn-primary">Salvar</button>
                                                                <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                            </div>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-12 col-md-7">
                                            <span class="px-3 pb-3" style="font-weight: 600">Plano Analítico</span>
                                            <div class="table-responsive">
                                                <table
                                                    class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                                    <thead class="border-header">
                                                        <tr>
                                                            <th class="w-100">Tema</th>
                                                            <th class="text-center">Acção</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($subject->lessions as $key=>$lession )
                                                        <tr class="border-bottom">
                                                            <td class="w-100">{{ $lession->name }}</td>
                                                            <td class="">
                                                                <div class="actions justify-content-center">
                                                                    <a href="{{ route('lessions.show', $lession->id) }}" class="btn btn-sm bg-success-light">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('lessions.edit', $lession->id) }}" class="btn btn-sm bg-danger-light">
                                                                        <i class="feather-edit"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="table-responsive">
                                                <table
                                                    class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                                    <thead class="border-header">
                                                        <tr>
                                                            <th class="w-100">Avaliação</th>
                                                            <th class="text-center">Depois da Aula</th>
                                                            <th class="text-center">Acção</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($subject->testes as $teste )
                                                    <tr class="border-bottom">
                                                        <td>{{ $teste->name }}</td>
                                                        <td class="text-center">{{ $teste->afterLession->name }}</td>
                                                        <td class="">
                                                            <div class="actions justify-content-center">
                                                                <a href="{{ route('subjects.testes.show', $teste->id) }}" class="btn btn-sm bg-danger-light">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('subjects.testes.edit', $teste->id) }}" class="btn btn-sm bg-danger-light">
                                                                    <i class="feather-edit"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach                                                     
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                                <div class="tab-pane" id="bottom-material">
                                    <div class="row">
                                        <div class="col-12 col-md-5">
                                            @if(isset($material))
                                            <form class="border" action="{{ route('subjects.material.update',$material->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div><span class="px-3 pt-4" style="font-weight: 600; display: block;">{{isset($material) ? 'Actualizar':'Adicionar'}}  Material</span></div>
                                                <div class="p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Título <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Digite o titulo" value="{{ old('name') ? old('name'):$material->name }}">
                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Tipo de Ficheiro <span class="login-danger">*</span></label>
                                                                <select class="form-control select  @error('type') is-invalid @enderror" name="type" id="file-type-select">
                                                                    <option selected disabled>Selecionar tipo de ficheiro</option>
                                                                    <option value="pdf"  {{ old("type") == 'pdf' || $material->type == 'pdf' ? "selected" :""}}>PDF</option>
                                                                    <option value="webm" {{ old("type") == 'webm' || $material->type == 'webm' ? "selected" :""}}>WebM</option>
                                                                    <option value="mp4"  {{ old("type") == 'mp4' || $material->type == 'mp4' ? "selected" :""}}>MP4</option>
                                                                    <option value="png"  {{ old("type") == 'png' || $material->type == 'png' ? "selected" :""}}>PNG</option>
                                                                    <option value="xslx" {{ old("type") == 'xslx' || $material->type == 'xslx' ? "selected" :""}}>Excel</option>
                                                                    <option value="jpeg" {{ old("type") == 'jpeg' || $material->type == 'jpeg' ? "selected" :""}}>JPEG</option>
                                                                    <option value="gif"  {{ old("type") == 'gif' || $material->type == 'gif' ? "selected" :""}}>GIF</option>
                                                                    <option value="bmp"  {{ old("type") == 'bmp' || $material->type == 'bmp' ? "selected" :""}}>BMP</option>
                                                                    <option value="mp3" {{ old("type") == 'mp3' || $material->type == 'mp3' ? "selected" :""}}>MP3</option>
                                                                    <option value="csv" {{ old("type") == 'csv' || $material->type == 'csv' ? "selected" :""}}>CSV</option>
                                                                    <option value="docx" {{ old("type") == 'docx' || $material->type == 'docx' ? "selected" :"Médio"}}>Word</option>
                                                                </select>
                                                                @error('type')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12" id="input_link" style="display: none;">
                                                            <div class="form-group local-forms">
                                                                <label>Link do video <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('link') is-invalid @enderror" name="link" placeholder="Cole o Link do video" value="{{ old('link') ? old('link'):$material->link }}">
                                                                @error('link')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12" id="input_file" style="display: none;">
                                                            <div class="form-group students-up-files">
                                                                <!-- <label>Logitipo (150px X 150px) <span class="login-danger">*</span></label> -->
                                                                <div class="uplod">
                                                                    <label class="file-upload image-upbtn mb-0 @error('file_path') is-invalid @enderror">
                                                                        Carregar o Ficheiro <input type="file" name="file_path" id="file-upload-input">
                                                                    </label>
                                                                    @error('file_path')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                    <div id="file-upload-status" style="display: none;">
                                                                        Ficheiro carregado: <span id="file-name"></span>
                                                                    </div>      
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <input type="hidden" name="subject_id" class="" value="{{$subject->id}}">
                                                        </div>                      
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="text-align: right;">
                                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                                    <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                </div>
                                            </form>
                                            @else
                                            <form class="border" action="{{ route('subjects.material.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div><span class="px-3 pt-4" style="font-weight: 600; display: block;">Adicionar Material</span></div>
                                                <div class="p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Título <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Digite o titulo" value="{{ old('title') }}">
                                                                @error('title')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Tipo de Ficheiro <span class="login-danger">*</span></label>
                                                                <select class="form-control select  @error('type') is-invalid @enderror" name="type" id="file-type-select">
                                                                    <option selected disabled>Selecionar tipo de ficheiro</option>
                                                                    <option value="pdf"  {{ old("type") == "pdf" ? "selected" :""}}>PDF</option>
                                                                    <option value="webm" {{ old("type") == 'webm' ? "selected" :""}}>WebM</option>
                                                                    <option value="mp4"  {{ old("type") == 'mp4' ? "selected" :""}}>MP4</option>
                                                                    <option value="png"  {{ old("type") == 'png' ? "selected" :""}}>PNG</option>
                                                                    <option value="xslx" {{ old("type") == 'xslx' ? "selected" :""}}>Excel</option>
                                                                    <option value="jpeg" {{ old("type") == 'jpeg' ? "selected" :""}}>JPEG</option>
                                                                    <option value="gif"  {{ old("type") == 'gif' ? "selected" :""}}>GIF</option>
                                                                    <option value="bmp"  {{ old("type") == 'bmp' ? "selected" :""}}>BMP</option>
                                                                    <option value="mp3" {{ old("type") == "mp3" ? "selected" :""}}>MP3</option>
                                                                    <option value="csv" {{ old("type") == 'csv' ? "selected" :""}}>CSV</option>
                                                                    <option value="docx" {{ old("type") == 'docx' ? "selected" :"Médio"}}>Word</option>
                                                                </select>
                                                                @error('type')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12" id="input_link" style="display: none;">
                                                            <div class="form-group local-forms">
                                                                <label>Link do video <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('link') is-invalid @enderror" name="link" placeholder="Cole o Link do video" value="{{ old('link') }}">
                                                                @error('link')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12" id="input_file" style="display: none;">
                                                            <div class="form-group students-up-files">
                                                                <!-- <label>Logitipo (150px X 150px) <span class="login-danger">*</span></label> -->
                                                                <div class="uplod">
                                                                    <label class="file-upload image-upbtn mb-0 @error('file_path') is-invalid @enderror">
                                                                        Carregar o Ficheiro <input type="file" name="file_path" id="file-upload-input">
                                                                    </label>
                                                                    @error('file_path')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                    <div id="file-upload-status" style="display: none;">
                                                                        Ficheiro carregado: <span id="file-name"></span>
                                                                    </div>      
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <input type="hidden" name="subject_id" class="" value="{{$subject->id}}">
                                                        </div>                      
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="text-align: right;">
                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                    <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                </div>
                                            </form>
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-7">
                                            <div class="table-responsive">
                                                <table
                                                    class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                                    <thead class="border-header">
                                                        <tr>
                                                            <th class="w-100">Material</th>
                                                            <th class="text-center">Ficheiro</th>
                                                            <th class="text-center">Acção</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($subject->materials as $material )
                                                    <tr class="border-bottom">
                                                        <td>{{ $material->name }}</td>
                                                        <td class="text-center">{{ $material->type }}</td>
                                                        <td class="">
                                                            <div class="actions justify-content-center">
                                                                <!-- <a href="/{{ $material->file_path }}" class="btn btn-sm bg-success-light">
                                                                    <i class="fas fa-eye"></i>
                                                                </a> -->
                                                                @if (in_array($material->type, ['webm', 'mp4', 'mp3']))
                                                                    <a href="{{ $material->file_path }}" class="btn btn-sm bg-success-light">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="/{{ $material->file_path }}" class="btn btn-sm bg-success-light">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                @endif
                                                                <a href="{{ route('subjects.material.edit', $material->id) }}" class="btn btn-sm bg-danger-light">
                                                                    <i class="feather-edit"></i>
                                                                </a>
                                                                <!-- <a class="btn btn-sm bg-danger-light text-danger student_delete" data-bs-toggle="modal" data-bs-target="#studentUser">
                                                                    <i class="feather-trash-2 me-1"></i>
                                                                </a> -->
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach  
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="border-top">
            <p>Copyright © 2023 Procul Discipulus.</p>
        </footer>
    </div>
    @section('script')
    <script>
        $(document).ready(function() {
            $("#file-type-select").select2();
            $("#file-type-select").change(function() {
                const selectedType = $(this).val();
                const externalTypes = ['webm','mp4','mp3'];
                if (externalTypes.includes(selectedType)) {
                    $("#input_link").show();
                    $("#input_file").hide();
                } else {
                    $("#input_link").hide();
                    $("#input_file").show();
                }
            });
            $('#file-upload-input').change(function() {
                var fileName = $(this).val().split('\\').pop();
                $('#file-name').text(fileName);
                $('#file-upload-status').show();
            });
        });
    </script>
    <script>
        function setActiveTab(tabName) {
            $('#nav-tabs a[data-bs-toggle="tab"]').removeClass('active');
            $('#nav-tabs a[data-bs-toggle="tab"][href="#' + tabName + '"]').addClass('active');
            $('#content-pane .tab-pane').removeClass('active');
            $('#'+tabName).addClass('active');
            if(tabName == 'bottom-lessions'){
                var contentTab = '{{ $contentTab }}';
                if(contentTab === 'lession'){
                    $('[href="#tabLinkTest"]').removeClass('active');
                    $('[href="#tabLinkLession"]').addClass('active');

                    $('#tabLinkTest').removeClass('active');
                    $('#tabLinkLession').addClass('active');
                }else{
                    $('[href="#tabLinkLession"]').removeClass('active');
                    $('[href="#tabLinkTest"]').addClass('active');

                    $('#tabLinkLession').removeClass('active');
                    $('#tabLinkTest').addClass('active');
                }                
            }
        }
        var activeTab = '{{ $activeTab }}';
        if (activeTab) {
            setActiveTab(activeTab);
        }
    </script>

    @endsection    
@endsection
