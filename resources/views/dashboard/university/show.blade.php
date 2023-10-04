
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
    </style>
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class='course-header'>
                <div id="course" class="w-100">
                    <img src="{{ URL::to($university->photo) }}" alt="Profile" class="university">
                    <div class="w-100">
                        <div id="course-description" class='text-nowrap'>
                            <span class='name'>{{ $university->acronym }} - {{ $university->name }}</span>
                            <span class='degree'></span>
                        </div>
                        <div class="follow-group border-top w-100 p-2">
                            <div class="students-follows text-center">
                                <h4>{{ count($university->operators) }}</h4>
                                <h5>Operadores</h5>
                            </div>
                            <div class="students-follows text-center">
                                <h4>{{ count($university->courses) }}</h4>
                                <h5>Cursos</h5>
                            </div>
                            <div class="students-follows text-center">
                                <h4>2850</h4>
                                <h5>Estudantes</h5>
                            </div>
                            <button type="submit" class="btn btn-outline-primary">Novo Operador</button>
                            <button type="submit" class="btn btn-outline-primary">Novo Curso</button>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table comman-shadow">
                        <div class="card-body">
                            <!-- <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title"></h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="" class="btn btn-outline-gray me-2 active"><i class="feather-list"></i></a>
                                        <a href="" class="btn btn-outline-gray me-2"><i class="feather-grid"></i></a>
                                        <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Download</a>
                                        <a href="{{ route('universities.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div> -->
                            <span class='' style="font-weight: 600">Operadores</span>
                            <hr>
                            <div class="table-responsive">
                                <table class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                    <thead class="border-header">
                                        <tr>
                                            <th>#</th>
                                            <th>Nome</th>
                                            <th>Apelido</th>
                                            <th>Email</th>
                                            <th class="text-center">Acção</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($university->operators as $key=>$school )
                                        <tr class="border-bottom">
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $school->name }}</td>
                                            <td>{{ $school->surname }}</td>
                                            <td>{{ $school->email }}</td>
                                            <td class="">
                                                <div class="actions justify-content-center">
                                                    <a href="{{ route('schools.show', $school->id) }}" class="btn btn-sm bg-success-light">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('schools.edit', $school->id) }}" class="btn btn-sm bg-danger-light">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <a class="btn btn-sm bg-danger-light text-danger student_delete" data-bs-toggle="modal" data-bs-target="#studentUser">
                                                        <i class="feather-trash-2 me-1"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <span class='' style="font-weight: 600">Cursos</span>
                            <hr>
                            <div class="table-responsive">
                                <table class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                    <thead class="border-header">
                                        <tr>
                                            <th>#</th>
                                            <th>Nome do curso</th>
                                            <th>Grau conferido</th>
                                            <th>Duração</th>
                                            <th class="text-center">Acção</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($university->courses as $key=>$course )
                                        <tr class="border-bottom">
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $course->name }}</td>
                                            <td>{{ $course->degree }}</td>
                                            <td>{{ $course->duration }}</td>
                                            <td class="">
                                                <div class="actions justify-content-center">
                                                    <a href="{{ route('universities.show', $course->id) }}" class="btn btn-sm bg-success-light">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('universities.edit', $course->id) }}" class="btn btn-sm bg-danger-light">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <a class="btn btn-sm bg-danger-light text-danger student_delete" data-bs-toggle="modal" data-bs-target="#studentUser">
                                                        <i class="feather-trash-2 me-1"></i>
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
            </div>
        </div>
        <footer class="border-top">
            <p>Copyright © 2023 Procul Discipulus.</p>
        </footer>
    </div>
@endsection
