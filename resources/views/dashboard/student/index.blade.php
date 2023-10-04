
@extends('layouts.master')
@section('content')
    <style>
        .border-header{
            border-bottom: 2px solid black;
        }
    </style>
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header mb-3 pb-2 border-bottom justify-content-between">
                            <h3 class="page-title">Estudantes</h3>
                            <a href="{{ route('students.create') }}" class="btn btn-sm btn-success">
                                Novo <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table comman-shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-student table-hover table-center mb-0 table-striped" id="table-students">
                                    <thead class="border-header">
                                        <tr>
                                            <th>Nome</th>
                                            <th>Apelido</th>
                                            <th>E-mail</th>
                                            <th>Contacto</th>
                                            <th class="text-center">Acção</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $key=>$student )
                                        <tr class="border-bottom">
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->surname }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->phone }}</td>
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
