
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
            <br>
            <div class="row">
                <div class="col-12 d-flex">
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
                                    class="table star-student table-hover table-center table-striped" id="table-students">
                                    <thead  class="border-header">
                                        <tr>
                                            <th class="text-center">Código</th>
                                            <th>Nome Completo</th>
                                            <th class="text-center">E-mail</th>
                                            <th class="text-center">Acção</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $key=>$student )
                                        <tr class="border-bottom">
                                            <td class="text-center">{{ $student->id }}</td>
                                            <td class="text-nowrap">{{ $student->name }} {{ $student->surname }}</td>
                                            <td class="text-center">{{ $student->email }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('students.certificates', $student->id) }}" class="text-success">
                                                    <i class="feather-eye"></i>
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
    @section('script')
    	<!-- Datatable JS -->
		<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
        <script>
            $(document).ready(function() {
                $('#table-students').dataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
                }});
            });
        </script>    
    @endsection        
@endsection