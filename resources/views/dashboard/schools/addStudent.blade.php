
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
                <div class="col-12">
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
                                            @foreach($students as $student)
                                            <option value="{{$student->id}}" {{ old('student_id') == $student->id ? "selected" :""}}>{{$student->email}}</option>
                                            @endforeach
                                        </select>
                                        @error('student_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group local-forms">
                                        <label>Curso <span class="login-danger">*</span></label>
                                        <select class="form-control select  @error('course_id') is-invalid @enderror w-100" name="course_id" id="course-select">
                                            <option selected disabled>Selecionar o curso</option>
                                            @foreach($courses as $course)
                                            <option value="{{$course->id}}" {{ old('course_id') == $course->id ? "selected" :""}}>{{$course->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('course_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group local-forms">
                                        <label>Disciplina <span class="login-danger">*</span></label>
                                        <select class="form-control select  @error('subject_id') is-invalid @enderror w-100" name="subject_id" id="subject-select">
                                            <option selected disabled>Selecionar a disciplina</option>
                                        </select>
                                        @error('subject_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>                      
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
        <footer class="border-top">
            <p>Copyright © 2023 Procul Discipulus.</p>
        </footer>
    </div>
    @section('script')
    <script>
        $(document).ready(function() {
            $("#course-select").select2();
            $("#student-select").select2();
            $("#subject-select").select2();

            // Adicione o evento de mudança para o campo "Curso"
            $("#course-select").change(function() {
                var course_id = $(this).val();

                // Faz a requisição AJAX para buscar as disciplinas do curso selecionado
                $.ajax({
                    url: '/courses/' + course_id +'/subjects',
                    type: 'GET',
                    success: function(data) {
                        console.log('data',data)
                        // Limpa as opções anteriores e adiciona as novas opções retornadas pelo servidor
                        $("#subject-select").empty().append('<option selected disabled>Selecionar a disciplina</option>');
                        $.each(data, function(index, discipline) {
                            $("#subject-select").append('<option value="' + discipline.id + '">' + discipline.name + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
    @endsection 
@endsection