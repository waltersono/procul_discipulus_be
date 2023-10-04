
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
                            <h3 class="page-title">Cursos</h3>
                            <a href="{{ route('courses.create') }}" class="btn btn-sm btn-success">
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
                                    class="table border-0 star-student table-hover table-center mb-0 table-striped" id="table-courses">
                                    <thead class="border-header">
                                        <tr>
                                            <th>#</th>
                                            <th>Escola</th>
                                            <th>Nome do curso</th>
                                            <th>Grau conferido</th>
                                            <th>Duração</th>
                                            <th class="text-center">Acção</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($courses as $key => $course )
                                        <tr class="border-bottom">
                                            <td>{{ ++$key }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#"class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ URL::to($course->university->photo) }}" alt="User Image">
                                                    </a>
                                                </h2>
                                            </td>
                                            <td>{{ $course->name }}</td>
                                            <td>{{ $course->degree }}</td>
                                            <td>{{ $course->duration }}</td>
                                            <td class="">
                                                <div class="actions justify-content-center">
                                                    <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm bg-success-light">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm bg-danger-light">
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
    {{-- model student delete --}}
    <div class="modal fade contentmodal" id="studentUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content doctor-profile">
                <div class="modal-header pb-0 border-bottom-0  justify-content-end">
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i
                        class="feather-x-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="delete-wrap text-center">
                            <div class="del-icon">
                                <i class="feather-x-circle"></i>
                            </div>
                            <input type="hidden" name="id" class="e_id" value="">
                            <input type="hidden" name="avatar" class="e_avatar" value="">
                            <h2>Sure you want to delete</h2>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-success me-2">Yes</button>
                                <a class="btn btn-danger" data-bs-dismiss="modal">No</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @section('script')
    	<!-- Datatable JS -->
		<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
        <script>
            $(document).ready(function() {
                $('#table-courses').dataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
                }});
            });
        </script>    
    @endsection
@endsection
