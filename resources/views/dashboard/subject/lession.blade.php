
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

    <div class="page-wrapper bg-white">
        <div class="content container-fluid">            
            <div class='course-header'>
                <div id="course">
                    <img src="{{ URL::to($lession->subject->course->university->photo) }}" alt="Profile" class="university">
                    <div id="course-description" class='text-nowrap'>
                        <span class='name'>{{ $lession->subject->name }}</span>
                        <span class="type">Tipo de disciplina: {{ $lession->subject->type }}</span>
                        <span class=''>Carga Horária: {{ $lession->subject->time }}</span>
                        <span class=''>Créditos: {{ $lession->subject->credits }}</span>
                    </div>
                </div>
            </div>
            <br>
            {{-- message --}}
            {!! Toastr::message() !!}           
            <div class="row">
                <div class="col-sm-12">
                    <div class="">
                        <div class="">
                            <ul class="nav nav-tabs nav-tabs-bottom">
                                <li class="nav-item"><a class="nav-link active" href="#bottom-lession" data-bs-toggle="tab"><span style="font-weight: bold;">Geral</span></a></li>
                                <li class="nav-item"><a class="nav-link" href="#bottom-quiz" data-bs-toggle="tab"><span style="font-weight: bold;">Exercicios de aplicação</span></a></li>
                            </ul>
                            <div class="tab-content mb-3">
                                <div class="tab-pane active show" id="bottom-lession">
                                    <div class="row">
                                        <div><span class="px-3 pb-2" style="font-weight: 600; display: block;">{{$lession->name}}</span></div>
                                        <div class="col-12 col-md-5">
                                            <form class="border" action="{{ route('lessions.summary') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div><span class="px-3 pt-4" style="font-weight: 600; display: block;">Adicionar tópico</span></div>
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
                                                            <input type="hidden" name="lession_id" class="" value="{{$lession->id}}">
                                                        </div>                      
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="text-align: right;">
                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                    <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-12 col-md-7">
                                            <div class="table-responsive">
                                                <table
                                                    class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                                    <thead class="border-header">
                                                        <tr>
                                                            <th class="w-100">Título</th>
                                                            <th class="text-center">Ficheiro</th>
                                                            <th class="text-center">Acção</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($lession->summary as $topic )
                                                    <tr class="border-bottom">
                                                        <td>{{ $topic->name }}</td>
                                                        <td class="text-center">{{ $topic->type }}</td>
                                                        <td class="">
                                                            <div class="actions justify-content-center">
                                                                @if (in_array($topic->type, ['webm', 'mp4', 'mp3']))
                                                                <a href="{{ $topic->file_path }}" class="btn btn-sm bg-success-light">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                @else
                                                                <a href="/{{ $topic->file_path }}" class="btn btn-sm bg-success-light">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                @endif
                                                                <a href="{{ route('lessions.edit', $topic->id) }}" class="btn btn-sm bg-danger-light">
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
                                <div class="tab-pane" id="bottom-quiz">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end align-items-center my-2">
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="collapse" data-bs-target="#add-question">Adicionar</button>
                                            </div>
                                            <form class="border mb-4 collapse" action="{{ route('subjects.question.store') }}" method="POST" enctype="multipart/form-data" id="add-question">
                                                @csrf
                                                <div><span class="px-3 pt-4" style="font-weight: 600; display: block;">Adicionar Questão</span></div>
                                                <div class="p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Pergunta <span class="login-danger">*</span></label>
                                                                <textarea rows="5" cols="5" class="form-control @error('question') is-invalid @enderror" placeholder="Informe a Pergunta" name="question"></textarea>
                                                                @error('question')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Alternativa A <span class="login-danger">*</span></label>
                                                                <textarea rows="5" cols="5" class="form-control @error('optionA') is-invalid @enderror" placeholder="Informe a Alternativa A" name="optionA"></textarea>
                                                                @error('optionA')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Alternativa B <span class="login-danger">*</span></label>
                                                                <textarea rows="5" cols="5" class="form-control @error('optionB') is-invalid @enderror" placeholder="Informe a Alternativa B" name="optionB"></textarea>
                                                                @error('optionB')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Alternativa C <span class="login-danger">*</span></label>
                                                                <textarea rows="5" cols="5" class="form-control @error('optionC') is-invalid @enderror" placeholder="Informe a Alternativa C" name="optionC"></textarea>
                                                                @error('optionC')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group local-forms">
                                                                <label>Alternativa D <span class="login-danger">*</span></label>
                                                                <textarea rows="5" cols="5" class="form-control @error('optionD') is-invalid @enderror" placeholder="Informe a Alternativa D" name="optionD"></textarea>
                                                                @error('optionD')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-4">
                                                            <div class="form-group local-forms">
                                                                <label>Alternativa correcta <span class="login-danger">*</span></label>
                                                                <select class="form-control select  @error('alterativa_correcta') is-invalid @enderror" name="alterativa_correcta">
                                                                    <option selected disabled>Selecionar a correcta</option>
                                                                    <option value="A" {{ old('alterativa_correcta') == 'A' ? "selected" : "" }}>Alternativa A</option>
                                                                    <option value="B" {{ old('alterativa_correcta') == 'B' ? "selected" : "" }}>Alternativa B</option>
                                                                    <option value="C" {{ old('alterativa_correcta') == 'C' ? "selected" : "" }}>Alternativa C</option>
                                                                    <option value="D" {{ old('alterativa_correcta') == 'D' ? "selected" : "" }}>Alternativa D</option>
                                                                </select>
                                                                @error('alterativa_correcta')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <input type="hidden" name="subject_id" class="" value="{{$lession->subject->id}}">
                                                            <input type="hidden" name="lession_id" class="" value="{{$lession->id}}">
                                                        </div>                 
                                                    </div>
                                                </div>
                                                <div class="card-footer" style="text-align: right;">
                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                    <button type="reset" class="btn btn-outline-danger">Limpar</button>
                                                </div>
                                            </form>
                                            <div class="">
                                                @foreach($lession->questions as $question)
                                                <div class="border-bottom d-flex justify-content-between align-items-center mb-2">
                                                    <div class="col-12 col-md-10"><span style="font-weight: 600">{{$loop->iteration}}.{{ $question->question }}</span></div>
                                                    <a class="btn btn-sm collapsed" data-bs-toggle="collapse" href="#question{{$question->id}}">
                                                        <i class="fa fa-angle-up" data-bs-toggle="tooltip" title="" data-bs-original-title="fa fa-angle-up" aria-label="fa fa-angle-up"></i>
                                                    </a>
                                                </div>
                                                <div id="question{{$question->id}}" class="collapse">
                                                    <div class="row mb-2">
                                                        <div class="col-12 col-md-2"><span style="font-weight: 600">Alternativa A</span></div>
                                                        <div class="col-12 col-md-10">{{ $question->optionA }}</div>
                                                    </div>    
                                                    <div class="row mb-2">
                                                        <div class="col-12 col-md-2"><span style="font-weight: 600">Alternativa B</span></div>
                                                        <div class="col-12 col-md-10">{{ $question->optionB }}</div>
                                                    </div>    
                                                    <div class="row mb-2">
                                                        <div class="col-12 col-md-2"><span style="font-weight: 600">Alternativa C</span></div>
                                                        <div class="col-12 col-md-10">{{ $question->optionC }}</div>
                                                    </div>    
                                                    <div class="row">
                                                        <div class="col-12 col-md-2"><span style="font-weight: 600">Alternativa D</span></div>
                                                        <div class="col-12 col-md-10">{{ $question->optionD }}</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 col-md-2"><span style="font-weight: 600">Alternativa Correcta</span></div>
                                                        <div class="col-12 col-md-10">{{ $question->correct }}</div>
                                                    </div>
                                                </div>    
                                                @endforeach
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
                var fileName = $(this).val().split('\\').pop(); // Obter apenas o nome do arquivo, removendo o caminho
                $('#file-name').text(fileName);
                $('#file-upload-status').show();
            });
        });
    </script>
    @endsection    
@endsection
