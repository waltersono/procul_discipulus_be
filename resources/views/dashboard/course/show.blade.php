
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

    </style>

    <div class="page-wrapper">
        <div class="content container-fluid">            
            <div class='course-header'>
                <div id="course">
                    <img src="{{ URL::to($course->university->photo) }}" alt="Profile" class="university">
                    <div id="course-description" class='text-nowrap'>
                        <span class='name'>Curso de {{ $course->name }}</span>
                        <span class='degree'>Grau conferido: {{ $course->degree }}</span>
                        <span class=''>Duração: {{ $course->duration }}</span>
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
                            <h5>Disciplinas</h5>
                            <h4>{{ count($course->subjects) }}</h4>
                        </div>
                        <div class="students-follows text-center">
                            <h5>Estudantes</h5>
                            <h4>{{ count($course->users) }}</h4>
                        </div>
                        <!-- <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addStudent"><i class="fas fa-plus"></i> Estudante</button> -->
                        <!-- <button type="button" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Disciplina</button> -->
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <div class="">
                        <div class="">
                            <ul class="nav nav-tabs nav-tabs-bottom">
                                <li class="nav-item"><a class="nav-link active" href="#bottom-tab1" data-bs-toggle="tab">Geral</a></li>
                                <li class="nav-item"><a class="nav-link" href="#bottom-tab2" data-bs-toggle="tab">Disciplinas</a></li>
                                <li class="nav-item"><a class="nav-link" href="#bottom-tab3" data-bs-toggle="tab">Estudantes</a></li>
                            </ul>
                            <div class="tab-content bg-white py-3">
                                <div class="tab-pane active show" id="bottom-tab1">
                                    <p class='my-1'>Em geral, {{$course->description}}.</p>
                                    <span class='' style="font-weight: 600">Requisitos de acesso</span>
                                    <ul class='objectives-list'>
                                        @forelse ($course->requirements as $item)
                                        <li><i class="fas fa-check"></i> <span>{{$item->name}}</span> </li>
                                        @empty
                                        <li>No requirements found.</li>
                                        @endforelse
                                    </ul>
                                    <span class='' style="font-weight: 600">Perfis ocupacionais</span>
                                    <p class='mb-1'>{{$course->skills_description}}</p>
                                    <ul class='objectives-list'>
                                        @forelse ($course->skills as $item)
                                        <li><i class="fas fa-check"></i> <span>{{$item->name}}</span> </li>
                                        @empty
                                        <li>No requirements found.</li>
                                        @endforelse
                                    </ul>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <form class="border" action="{{ route('courses.requirement.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div><span class="px-3 pt-4" style="font-weight: 600; display: block;">Adicionar Requisitos de Acesso</span></div>
                                                <div class="p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Requisito de Acesso <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('requirement') is-invalid @enderror" name="requirement" placeholder="Informe o Requisito" value="{{ old('requirement') }}">
                                                                @error('requirement')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>                      
                                                        <input type="hidden" name="course_id" class="" value="{{$course->id}}">
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="text-align: right;">
                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                    <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <form class="border" action="{{ route('courses.skill.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div><span class="px-3 pt-4" style="font-weight: 600; display: block;">Adicionar Perfis Ocupacionais</span></div>
                                                <div class="p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Perfil Ocupacional <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('skill') is-invalid @enderror" name="skill" placeholder="Informe o Perfil Ocupacional" value="{{ old('skill') }}">
                                                                @error('skill')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div> 
                                                        <input type="hidden" name="course_id" class="" value="{{$course->id}}">
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
                                <div class="tab-pane" id="bottom-tab2">
                                    <span class="px-3 pb-3" style="font-weight: 600">Plano de Estudos</span>
                                    <div class="table-responsive">
                                        <table
                                            class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                            <thead class="border-header">
                                                <tr>
                                                    <th class="text-center">Ano</th>
                                                    <th class="text-center">Semestre</th>
                                                    <th>Disciplina</th>
                                                    <th class="text-center">Tipo</th>
                                                    <th class="text-center">Horas</th>
                                                    <th class="text-center">Créditos</th>
                                                    <th class="text-center">Acção</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($course->subjects as $key=>$subject )
                                                <tr class="border-bottom">
                                                    <td class="text-center">{{ $subject->level }}°</td>
                                                    <td class="text-center">{{ $subject->semester }}</td>
                                                    <td>{{ $subject->name }}</td>
                                                    <td class="text-center">{{ $subject->type }}</td>
                                                    <td class="text-center">{{ $subject->time }}</td>
                                                    <td class="text-center">{{ $subject->credits }}</td>
                                                    <td class="text-center">
                                                        <div class="actions text-center">
                                                            <a href="{{ route('subjects.show', $subject->id) }}" class="btn btn-sm bg-success-light">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-sm bg-danger-light">
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
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            @if(isset($subject_edit))
                                            <form class="border" action="{{ route('subjects.update', $subject_edit->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div><span class="px-3 pt-4" style="font-weight: 600; display: block;">Editar Disciplina</span></div>
                                                <div class="p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Nome da disciplina <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Informe o nome" value="{{ old('name') ? old('name') : $subject_edit->name }}">
                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-4">
                                                            <div class="form-group local-forms">
                                                                <label>Nível <span class="login-danger">*</span></label>
                                                                <select class="form-control select  @error('level') is-invalid @enderror" name="level">
                                                                    <option selected disabled>Selecionar o Nível</option>
                                                                    <option value="1" {{ old('level') == '1' || $subject_edit->level == '1' ? "selected" :""}}>1</option>
                                                                    <option value="2" {{ old('level') == '2' || $subject_edit->level == '2' ? "selected" :""}}>2</option>
                                                                    <option value="3" {{ old('level') == '3' || $subject_edit->level == '3' ? "selected" :""}}>3</option>
                                                                    <option value="4" {{ old('level') == '4' || $subject_edit->level == '4' ? "selected" :""}}>4</option>
                                                                    <option value="5" {{ old('level') == '5' || $subject_edit->level == '5' ? "selected" :""}}>5</option>
                                                                    <option value="6" {{ old('level') == '6' || $subject_edit->level == '6' ? "selected" :""}}>6</option>
                                                                    <option value="7" {{ old('level') == '7' || $subject_edit->level == '7' ? "selected" :""}}>7</option>
                                                                    <option value="8" {{ old('level') == '8' || $subject_edit->level == '8' ? "selected" :""}}>8</option>
                                                                    <option value="9" {{ old('level') == '9' || $subject_edit->level == '9' ? "selected" :""}}>9</option>
                                                                    <option value="10" {{ old('level') == '10' || $subject_edit->level == '10' ? "selected" :""}}>10</option>
                                                                </select>
                                                                @error('level')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-4">
                                                            <div class="form-group local-forms">
                                                                <label>Semestre <span class="login-danger">*</span></label>
                                                                <select class="form-control select  @error('semester') is-invalid @enderror" name="semester">
                                                                    <option selected disabled>Selecionar o Semestre</option>
                                                                    <option value="1" {{ old('semester') == '1' || $subject_edit->semester == '1' ? "selected" :""}}>1</option>
                                                                    <option value="2" {{ old('semester') == '2' || $subject_edit->semester == '2' ? "selected" :""}}>2</option>
                                                                </select>
                                                                @error('semester')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-4">
                                                            <div class="form-group local-forms">
                                                                <label>Tipo de Disciplina <span class="login-danger">*</span></label>
                                                                <select class="form-control select  @error('type') is-invalid @enderror" name="type">
                                                                    <option selected disabled>Selecionar o tipo</option>
                                                                    <option value="Complementar" {{ old('type') == 'Complementar' || $subject_edit->type == 'Complementar' ? "selected" :""}}>Complementar</option>
                                                                    <option value="Nuclear" {{ old('type') == 'Nuclear' || $subject_edit->type == 'Nuclear' ? "selected" :""}}>Nuclear</option>
                                                                </select>
                                                                @error('type')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <div class="form-group local-forms">
                                                                <label>Horas <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('time') is-invalid @enderror" name="time" placeholder="Informe a duração" value="{{ old('time') ? old('time') : $subject_edit->time }}">
                                                                @error('time')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <div class="form-group local-forms">
                                                                <label>Créditos <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('credits') is-invalid @enderror" name="credits" placeholder="Informe os créditos" value="{{ old('credits') ? old('credits') : $subject_edit->credits }}">
                                                                @error('credits')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>                                                        
                                                        <input type="hidden" name="course" class="" value="{{$course->id}}">
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="text-align: right;">
                                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                                    <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                </div>
                                            </form>
                                            @else
                                            <form class="border" action="{{ route('subjects.store') }}" method="POST">
                                                @csrf
                                                <div><span class="px-3 pt-4" style="font-weight: 600; display: block;">Adicionar Disciplina</span></div>
                                                <div class="p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Nome da disciplina <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Informe o nome" value="{{ old('name') }}">
                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-4">
                                                            <div class="form-group local-forms">
                                                                <label>Nível <span class="login-danger">*</span></label>
                                                                <select class="form-control select  @error('level') is-invalid @enderror" name="level">
                                                                    <option selected disabled>Selecionar o Nível</option>
                                                                    <option value="1" {{ old('level') == '1' ? "selected" :""}}>1</option>
                                                                    <option value="2" {{ old('level') == '2' ? "selected" :""}}>2</option>
                                                                    <option value="3" {{ old('level') == '3' ? "selected" :""}}>3</option>
                                                                    <option value="4" {{ old('level') == '4' ? "selected" :""}}>4</option>
                                                                    <option value="5" {{ old('level') == '5' ? "selected" :""}}>5</option>
                                                                </select>
                                                                @error('level')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-4">
                                                            <div class="form-group local-forms">
                                                                <label>Semestre <span class="login-danger">*</span></label>
                                                                <select class="form-control select  @error('semester') is-invalid @enderror" name="semester">
                                                                    <option selected disabled>Selecionar o Semestre</option>
                                                                    <option value="1" {{ old('semester') == '1' ? "selected" :""}}>1</option>
                                                                    <option value="2" {{ old('semester') == '2' ? "selected" :""}}>2</option>
                                                                </select>
                                                                @error('semester')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-4">
                                                            <div class="form-group local-forms">
                                                                <label>Tipo de Disciplina <span class="login-danger">*</span></label>
                                                                <select class="form-control select  @error('type') is-invalid @enderror" name="type">
                                                                    <option selected disabled>Selecionar o tipo</option>
                                                                    <option value="Complementar" {{ old('type') == 'Complementar' ? "selected" :""}}>Complementar</option>
                                                                    <option value="Nuclear" {{ old('type') == 'Nuclear' ? "selected" :""}}>Nuclear</option>
                                                                </select>
                                                                @error('type')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <div class="form-group local-forms">
                                                                <label>Horas <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('time') is-invalid @enderror" name="time" placeholder="Informe a duração" value="{{ old('time') }}">
                                                                @error('time')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <div class="form-group local-forms">
                                                                <label>Créditos <span class="login-danger">*</span></label>
                                                                <input type="text" class="form-control @error('credits') is-invalid @enderror" name="credits" placeholder="Informe os créditos" value="{{ old('credits') }}">
                                                                @error('credits')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>                                                        
                                                        <input type="hidden" name="course" class="" value="{{$course->id}}">
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
                                <div class="tab-pane" id="bottom-tab3">
                                    <div class="row">
                                        <!-- <div class="col-12 col-md-5">
                                            <form class="border" action="{{ route('courses.student.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div><span class="px-3 pt-4" style="font-weight: 600; display: block;">Associar estudante ao curso</span></div>
                                                <div class="p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Estudante <span class="login-danger">*</span></label>
                                                                <select class="form-control select  @error('student_id') is-invalid @enderror w-100" name="student_id" id="student-select">
                                                                    <option selected disabled>Selecionar o estudante</option>
                                                                    @foreach($users as $user)
                                                                    <option value="{{$user->id}}" {{ old('student_id') == $user->id ? "selected" :""}}>{{$user->email}}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('student_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <input type="hidden" name="course_id" class="" value="{{$course->id}}">
                                                        </div>                      
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="text-align: right;">
                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                    <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                </div>
                                            </form>
                                        </div> -->
                                        <div class="col-12">
                                            <span class="pb-3" style="font-weight: 600">Lista dos estudantes</span>
                                            <div class="">
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table
                                                            class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                                            <thead class="border-header">
                                                                <tr>
                                                                    <th>Nome</th>
                                                                    <th>Apelido</th>
                                                                    <th>E-mail</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($course->users as $key => $student )
                                                                <tr class="border-bottom">
                                                                    <td>{{ $student->name }}</td>
                                                                    <td>{{ $student->surname }}</td>
                                                                    <td>{{ $student->email }}</td>
                                                                    <td class="">
                                                                        <div class="actions justify-content-center">
                                                                            <!-- <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm bg-success-light">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a> -->
                                                                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm bg-danger-light">
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
            </div>
        </div>
        <footer class="border-top">
            <p>Copyright © 2023 Procul Discipulus.</p>
        </footer>
    </div>
    @section('script')
    <script>
        $(document).ready(function() {
            $("#student-select").select2();
        });
    </script>
    @endsection
@endsection
