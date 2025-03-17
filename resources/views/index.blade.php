@extends('layouts.layout')

@section('title', 'Login')



@section('content')
<link rel="stylesheet" href="{{ asset('styles/index.css') }}">
    <div class="login-container">
        <div class="login-logo">
            <img src="{{asset('img/logo.png')}}" alt="Logo de la aplicación">
        </div>
        <div class="login-form">
            <h1>Iniciar Sesión</h1>
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nombre_usuario">Nombre de usuario:</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-input">
                    <span class="error-message" id="error_username"></span>
                </div>
                <div class="form-group">
                    <label for="pwd">Contraseña:</label>
                    <input type="password" id="pwd" name="pwd" class="form-input">
                    <span class="error-message" id="error_password"></span>
                    @if ($errors->has('nombre_usuario'))
                        <span class="error-message">{{ $errors->first('nombre_usuario') }}</span>
                    @endif
                </div>
                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/validaLogin.js') }}"></script>
@endsection