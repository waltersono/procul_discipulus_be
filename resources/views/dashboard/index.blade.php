
@extends('layouts.master')
@section('content')
{{-- message --}}

<div class="page-wrapper">
    <div class="content container-fluid">
        
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h3>{{count($universities)}}</h3>
                                <h6>Escolas</h6>
                            </div>
                            <div class="db-icon">
                                <img src="assets/img/icons/dash-icon-03.svg" alt="Dashboard Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h3>{{count($courses)}}</h3>
                                <h6>Cursos</h6>
                            </div>
                            <div class="db-icon">
                                <img src="assets/img/icons/teacher-icon-01.svg" alt="Dashboard Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h3>{{count($lessions)}}</h3>
                                <h6>Aulas</h6>
                            </div>
                            <div class="db-icon">
                                <img src="assets/img/icons/teacher-icon-02.svg" alt="Dashboard Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h3>{{count($students)}}</h3>
                                <h6>Estudantes</h6>
                            </div>
                            <div class="db-icon">
                                <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="d-flex">
                <div class="card flex-fill student-space comman-shadow">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title">Escolas</h5>
                        <ul class="chart-list-out student-ellips">
                            <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                class="table star-student table-hover table-center table-borderless table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">Logotipo</th>
                                        <th class="text-center">Acrônimo</th>
                                        <th>Nome</th>
                                        <th class="text-center">Cursos</th>
                                        <th class="text-center">Operadores</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($universities as $key=>$school )
                                    <tr class="border-bottom">
                                        <td class="text-center">
                                            <img class="avatar-img rounded-circle" src="{{ $school->photo }}" alt="School Image" width="25">
                                        </td>
                                        <td class="text-center">{{ $school->acronym }}</td>
                                        <td class="text-nowrap">{{ $school->name }}</td>
                                        <td class="text-center">{{ count($school->courses) }}</td>
                                        <td class="text-center">{{ count($school->operators) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 d-flex">
                <div class="card flex-fill student-space comman-shadow">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title">Estudantes</h5>
                        <ul class="chart-list-out student-ellips">
                            <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                class="table star-student table-hover table-center table-borderless table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nome Completo</th>
                                        <th class="text-center">Cursos</th>
                                        <th class="text-center">Aulas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $key=>$student )
                                    <tr class="border-bottom">
                                        <td class="text-nowrap">{{ $student->name }} {{ $student->surname }}</td>
                                        <td class="text-center">{{ count($student->courses) }}</td>
                                        <td class="text-center">{{ count($student->lessions) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 d-flex">
                <div class="card flex-fill student-space comman-shadow">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title">Cursos</h5>
                        <ul class="chart-list-out student-ellips">
                            <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                class="table star-student table-hover table-center table-borderless table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Escola</th>
                                        <th>Nome do curso</th>
                                        <th>Grau conferido</th>
                                        <th>Estudantes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $key=>$course )
                                    <tr class="border-bottom">
                                        <td>
                                            <img class="avatar-img rounded-circle" src="{{ $course->university->photo }}" alt="School Image" width="25">
                                        </td>
                                        <td class="text-center">{{ $course->name }}</td>
                                        <td class="text-center">{{ $course->degree }}</td>
                                        <td class="text-center">{{ count($course->users) }}</td>
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
