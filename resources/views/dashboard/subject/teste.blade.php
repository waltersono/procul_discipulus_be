
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
                    <img src="{{ URL::to($teste->subject->course->university->photo) }}" alt="Profile" class="university">
                    <div id="course-description" class='text-nowrap'>
                        <span class='name'>{{ $teste->subject->name }}</span>
                        <span class="type">Tipo de disciplina: {{ $teste->subject->type }}</span>
                        <span class=''>Carga Horária: {{ $teste->subject->time }}</span>
                        <span class=''>Créditos: {{ $teste->subject->credits }}</span>
                    </div>
                </div>
            </div>
            <br>
            {{-- message --}}
            {!! Toastr::message() !!}  
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <div><span class="px-3 pb-2" style="font-weight: 600; display: block;">{{$teste->name}}</span></div>
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="collapse" data-bs-target="#add-question">Adicionar Exercicio</button>
                    </div>
                    <form class="border mb-4 collapse" action="{{ route('subjects.question.testes.store') }}" method="POST" enctype="multipart/form-data" id="add-question">
                        @csrf
                        <div><span class="px-3 pt-4" style="font-weight: 600; display: block;"></span></div>
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
                                    <input type="hidden" name="teste_id" class="" value="{{$teste->id}}">
                                </div>                 
                            </div>
                        </div>
                        <div class="card-footer" style="text-align: right;">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <button type="reset" class="btn btn-outline-danger">Limpar</button>
                        </div>
                    </form>
                    <div class="">
                        @foreach($teste->questions as $question)
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
        <footer class="border-top">
            <p>Copyright © 2023 Procul Discipulus.</p>
        </footer>
    </div>
    @section('script')
    <script>
        $(document).ready(function() {
            
        });
    </script>
    @endsection    
@endsection
