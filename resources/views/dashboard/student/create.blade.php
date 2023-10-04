
@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header mb-3 pb-2 border-bottom">
                            <h3 class="page-title">Adicionar Estudante</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Estudantes</a></li>
                                <li class="breadcrumb-item active">Adicionar</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-sm-12">
                    <form class="card" action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Dados do Estudante</span></h5>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>E-mail <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Informe o e-mail" value="{{ old('email') }}">
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
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Informe o nome" value="{{ old('name') }}">
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
                                        <input type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" placeholder="Informe o apelido" value="{{ old('surname') }}">
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
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Informe o contacto" value="{{ old('phone') }}">
                                        @error('phone')
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
@endsection
