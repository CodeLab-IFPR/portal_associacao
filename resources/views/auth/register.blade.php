<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Formulário de Login e Registro</title>
  <!---Custom CSS File--->
  @vite('resources/css/styleLogin.css')    
</head>
<body>
  <div class="container">
    <input type="checkbox" id="check">
    <div class="login form">
      <header>Registrar</header>
      <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="text" name="name" placeholder="Digite seu nome" :value="old('name')" required autofocus autocomplete="name">
        <input type="email" name="email" placeholder="Digite seu e-mail" :value="old('email')" required autocomplete="username">
        <input type="password" name="password" placeholder="Digite sua senha" required autocomplete="new-password">
        <input type="password" name="password_confirmation" placeholder="Confirme sua senha" required autocomplete="new-password">
        <a href="{{ route('login') }}">Já registrado?</a>
        <input type="submit" class="button" value="Registrar">
      </form>
    </div>
  </div>
</body>
</html>