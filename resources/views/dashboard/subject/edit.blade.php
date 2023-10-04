
@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Editar Escola</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Escolas</a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-sm-12">
                    <form class="card" action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Dados do curso</span></h5>
                                </div>
                                <div class="col-12">
                                    <div class="form-group local-forms">
                                        <label>Nome do curso <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Informe o nome" value="{{ old('name') ? old('name') : $course->name }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Escola <span class="login-danger">*</span></label>
                                        <select class="form-control select  @error('university') is-invalid @enderror" name="university">
                                            <option selected disabled>Selecionar escola</option>
                                            @foreach($universities as $university)
                                            <option value="{{$university->id}}" {{ old('university') == $university->id || $course->university->id == $university->id ? "selected" :""}}>{{$university->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('university')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3">
                                    <div class="form-group local-forms">
                                        <label>Grau conferido <span class="login-danger">*</span></label>
                                        <select class="form-control select  @error('degree') is-invalid @enderror" name="degree">
                                            <option selected disabled>Selecionar o grau conferido</option>
                                            <option value="Doutoramento" {{ old('degree') == 'Doutoramento' || $course->degree == 'Doutoramento' ? "selected" :""}}>Doutoramento</option>
                                            <option value="Licenciatura" {{ old('degree') == 'Licenciatura' || $course->degree == 'Licenciatura' ? "selected" :"Licenciatura"}}>Licenciatura</option>
                                            <option value="Mestrado" {{ old('degree') == 'Mestrado' || $course->degree == 'Mestrado' ? "selected" :""}}>Mestrado</option>
                                            <option value="Médio" {{ old('degree') == 'Médio' || $course->degree == 'Médio' ? "selected" :"Médio"}}>Médio</option>
                                        </select>
                                        @error('degree')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3">
                                    <div class="form-group local-forms">
                                        <label>Duração <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('duration') is-invalid @enderror" name="duration" placeholder="Informe a duração" value="{{ old('duration') ? old('duration') : $course->duration }}">
                                        @error('duration')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group local-forms">
                                        <label>Descrição <span class="login-danger">*</span></label>
                                        <textarea rows="5" cols="5" class="form-control" placeholder="Informe a descrição do curso" name="description">{{ old('description') ? old('description') : $course->description }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group local-forms">
                                        <label>Descrição Ocupacional<span class="login-danger">*</span></label>
                                        <textarea rows="5" cols="5" class="form-control" placeholder="Informe a descrição ocupacional" name="skills_description">{{ old('skills_description') ? old('skills_description') : $course->skills_description }}</textarea>
                                        @error('skills_description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>                          
                            </div>
                        </div>
                        <div class="card-footer" style="text-align: right;">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <footer class="border-top">
            <p>Copyright © 2023 Procul Discipulus.</p>
        </footer>
    </div>
@endsection
