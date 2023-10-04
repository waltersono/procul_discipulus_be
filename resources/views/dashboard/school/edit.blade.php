
@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Editar Operador da Escola</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('schools.index') }}">Operadores da Escola</a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-sm-12">
                    <form class="card" action="{{ route('schools.update', $school->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Dados do Operador da Escola</span></h5>
                                </div>
                                <div class="col-12">
                                    <div class="form-group local-forms">
                                        <label>E-mail <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Informe o e-mail" value="{{ old('email') ? old('email') : $school->email }}">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Nome <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Informe o nome" value="{{ old('name') ? old('name') : $school->name }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Apelido <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" placeholder="Informe o Apelido" value="{{ old('surname') ? old('surname') : $school->surname }}">
                                        @error('surname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Contacto <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Informe o contacto" value="{{ old('phone') ? old('phone') : $school->phone }}">
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Escola <span class="login-danger">*</span></label>
                                        <select class="form-control select  @error('university') is-invalid @enderror" name="university" id="university">
                                            <option selected disabled>Selecionar escola</option>
                                            @foreach($universities as $university)
                                            <option value="{{$university->id}}" {{ old('university') == $university->id ? "selected" :""}}>{{$university->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('university')
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
