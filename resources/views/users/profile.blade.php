@extends('template')
@section('title', 'Perfil')
@section('content')

<div class="container">
    <div class="py-5 ">
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-3">Informação de Utilizador</h4>

                <form method="POST" action="{{route('Profile.edit', ['user' => $user])}}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="number" name="id" hidden value="{{$user->id}}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="nome" value="{{ old('nome') ?? $user->name }}">
                            @error('nome')
                            <div class="small text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    @if ($user->tipo != 'F')
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" value="{{ old('email') ?? $user->email }}">
                            @error('email')
                                <div class="small text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    @endif

                    </div>

                    @if($user->tipo == 'C')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>NIF</label>
                            <input type="text" class="form-control" name="nif" value="{{ old('nif') ?? ($user->cliente->nif ?? '') }}">
                            @error('nif')
                            <div class="small text-danger">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Morada</label>
                            <input type="text" class="form-control" name="morada" value="{{ old('morada') ?? ($user->cliente->endereco ?? '') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Tipo de Pagamento Predefinido</label>
                            <select class="custom-select " name="metodo_pagamento">
                                @foreach ($metodos as $metodo)
                                <option value="{{$metodo}}" {{$metodo == ($user->cliente->tipo_pagamento ?? '') ? 'selected' : ''}}>{{$metodo}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Referência de Pagamento Predefinida</label>
                            <input type="text" class="form-control" name="ref_pagamento" value="{{ old('ref_pagamento') ?? ($user->cliente->ref_pagamento ?? '') }}">
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="form-group">
                            <label for="inputFoto">Upload da foto</label>
                            <input type="file" class="form-control" name="foto" id="inputFoto">
                            @error('foto')
                            <div class="small text-danger">{{$message}}</div>
                            @enderror
                        </div>
                        @isset($user->foto_url)
                        <div class="form-group">
                            <img src="{{$user->foto_url ? asset('storage/fotos/' . $user->foto_url) : asset('img/default_img.png') }}" alt="Foto do utilizador" class="img-profile" style="max-width:100%">
                        </div>
                        @endisset
                    </div>

                    <button type="submit" class="btn btn-primary cart-update">Atualizar</button>
                    @isset($user->foto_url)
                    <button type="submit" class="btn btn-danger cart-update" name="deletefoto" form="form_delete_photo">Apagar Foto</button>
                    @endisset
                </form>

                <hr class="mb-4">

                @if ($user->tipo != 'F')
                    <form action="{{route('Profile.password', auth()->user())}}" method="POST" id="form_password">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Password Atual</label>
                                <input type="password" class="form-control" name="password_atual">
                                @error('password_atual')
                                <div class="small text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nova Password</label>
                                <input type="password" class="form-control" name="nova_password">
                                @error('nova_password')
                                <div class="small text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Confirmar Nova Password</label>
                                <input type="password" class="form-control" name="conf_nova_password">
                                @error('conf_nova_password')
                                <div class="small text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary cart-update" form="form_password" style="margin-bottom: 50px">Atualizar Password</button>
                    </form>
                @endif
                <button class="btn btn-primary mb-4" onclick="goBack()">Voltar</button>
                <form id="form_delete_photo" action="{{route('Profile.foto.destroy')}}" method="POST">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        </div>
    </div>
</div>
<script>
    function goBack() {
      window.history.back();
    }
    </script>
@endsection
