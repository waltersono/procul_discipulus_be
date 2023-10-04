
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
            
            border-right: 1px solid #e4e8f7;
        }
        
        #course-description {
            display: flex;
            flex-direction: column;
            row-gap: 0.25rem;
            padding: 0.5rem;
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
                    <img src="{{ URL::to(Auth::user()->activeSchool()->photo) }}" alt="Profile" class="university">
                    <div class="w-100">
                        <div id="course-description" class='text-nowrap'>
                            <span class='name'>{{ Auth::user()->activeSchool()->acronym }}</span>
                            <span class='degree'>{{ Auth::user()->activeSchool()->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="course-header flex-column p-3">
                <h5 class="mb-0">Estudante: {{$student->name}} {{$student->surname}}</h5>
                <span class='name'>{{$student->email}}</span>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table comman-shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                    <thead class="border-header">
                                        <tr>
                                            <th>Escola</th>
                                            <th>Disciplina</th>
                                            <th>Curso</th>
                                            <th class="text-center">Nivel</th>
                                            <th>Estado</th>
                                            <th class="text-center">Acção</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($student->subjects as $key => $subject )
                                        <tr class="border-bottom">
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#"class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ URL::to($subject->course->university->photo) }}" alt="User Image">
                                                    </a>
                                                </h2>
                                            </td>
                                            <td>{{ $subject->name }}</td>
                                            <td>{{ $subject->course->name }}</td>
                                            <td class="text-center">{{ $subject->level }} anos</td>
                                            <td>
                                                @if($subject->pivot->status)
                                                <span class="btn btn-sm btn-primary text-white">
                                                    Activo
                                                </span>
                                                @else
                                                <span class="btn btn-sm btn-danger text-white">
                                                    Inactivo
                                                </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($subject->pivot->status)
                                                <a href="#" target="_blank" onclick="event.preventDefault(); document.getElementById('status-student{{$subject->id}}').submit();" class="btn btn-sm btn-light">
                                                    Bloquear <i class="feather-lock"></i>
                                                </a>
                                                @else
                                                <a href="#" target="_blank" onclick="event.preventDefault(); document.getElementById('status-student{{$subject->id}}').submit();" class="btn btn-sm btn-light">
                                                    Desbloquear <i class="feather-unlock"></i>
                                                </a>
                                                @endif
                                            </td>
                                            <form id="status-student{{$subject->id}}" action="{{route('subjects.student.status')}}" method="POST" class="d-none">
                                                @csrf
                                                <input type="hidden" name="subject_id" value="{{$subject->id}}">
                                                <input type="hidden" name="student_id" value="{{$student->id}}">
                                            </form>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card flex-fill student-space comman-shadow">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title">Certificados de Disciplinas</h5>
                            <ul class="chart-list-out student-ellips">
                                <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                    <thead class="border-header">
                                        <tr>
                                            <th class="">Disciplina</th>
                                            <th class="">Curso</th>
                                            <th class="text-center">Média</th>
                                            <th class="text-center">Acção</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subjects as $subject )
                                        <tr class="border-bottom">
                                            <td class="text-nowrap">{{ $subject['subject'] }}</td>
                                            <td class="text-nowrap">{{ $subject['course'] }}</td>
                                            <td class="text-center">{{ $subject['media'] }}</td>
                                            <td class="text-center">
                                                <a href="#" target="_blank" onclick="event.preventDefault(); document.getElementById('download-certificate{{$subject['id']}}').submit();" class="btn btn-sm btn-success text-white">
                                                    Baixar <i class="feather-download"></i>
                                                </a>
                                            </td>
                                            <form id="download-certificate{{$subject['id']}}" action="{{route('students.certificates.generate')}}" method="POST" class="d-none">
                                                @csrf
                                                <input type="hidden" name="subject_id" value="{{$subject['id']}}">
                                                <input type="hidden" name="student_id" value="{{$student->id}}">
                                            </form>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card flex-fill student-space comman-shadow">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title">Certificados de Cursos</h5>
                            <ul class="chart-list-out student-ellips">
                                <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                    <thead class="border-header">
                                        <tr>
                                            <th class="">Curso</th>
                                            <th class="text-center">Média</th>
                                            <th class="text-center">Acção</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($courses as $course )
                                        <tr class="border-bottom">
                                            <td class="text-nowrap">{{ $course['course'] }}</td>
                                            <td class="text-center">{{ $course['media'] }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('students.certificates', $course['id']) }}" class="btn btn-sm btn-success text-white">
                                                    Baixar <i class="feather-download"></i>
                                                </a>
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
