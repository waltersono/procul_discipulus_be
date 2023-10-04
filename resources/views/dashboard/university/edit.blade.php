
@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header pb-2 border-bottom">
                            <h3 class="page-title">Editar Escola</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('universities.index') }}">Escolas</a></li>
                                <li class="breadcrumb-item active">Editar</li>
                            </ul>
                        </div>
                    </div>    
                </div>
            </div>
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-sm-12">
                    <form class="card" action="{{ route('universities.update', $university->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Dados da Escola</span></h5>
                                </div>
                                <div class="col-12">
                                    <div class="form-group local-forms">
                                        <label>Nome <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Informe o Nome" value="{{ old('name') ? old('name') : $university->name }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Acrônimo <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('acronym') is-invalid @enderror" name="acronym" placeholder="Informe o Acrônimo" value="{{ old('acronym') ? old('acronym') : $university->acronym }}">
                                        @error('acronym')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group students-up-files">
                                        <!-- <label>Logitipo (150px X 150px) <span class="login-danger">*</span></label> -->
                                        <div class="uplod">
                                            <label class="file-upload image-upbtn mb-0 @error('photo') is-invalid @enderror">
                                                Carregar Logotipo <input type="file" name="photo" id="file-upload-input">
                                            </label>
                                            @error('photo')
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
    @section('script')
    <script>
        $(document).ready(function() {
            $('#file-upload-input').change(function() {
                var fileName = $(this).val().split('\\').pop(); // Obter apenas o nome do arquivo, removendo o caminho
                $('#file-name').text(fileName);
                $('#file-upload-status').show();
            });
        });
    </script>
    @endsection    
@endsection
