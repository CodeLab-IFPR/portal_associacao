@section('title', __('Method Not Allowed'))
@section('code', '405')
@section('message', __('Method Not Allowed'))

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Esta ação não está disponível neste endereço. Volte à página anterior e tente novamente.">
    <title>@yield('code')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <main class="container d-flex flex-column justify-content-center align-items-center min-vh-100 text-center">
        <div class="card shadow-sm rounded">
            <div class="card-body bg-light">
                <h1 class="display-1 fw-bold text-dark" aria-hidden="true">@yield('code')</h1>
                <p class="lead fs-4 text-dark">@yield('message')</p>
                <div class="container">
                    <p class="mb-4 text-muted text-center">
                        <strong>Ops! Essa ação não pode ser feita por aqui. ⛔</strong></p>
                    <p>
                        O endereço que você acessou existe, mas não aceita esse tipo de operação.
                        <br> Volte à página anterior e tente novamente pelo botão correspondente. Se o problema persistir, fale com o suporte. 🚀
                    </p>
                </div>
                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <a href="{{ url()->previous() }}" class="btn btn-primary">Voltar à Página Anterior</a>
                    <a href="/" class="btn btn-outline-primary">Voltar à Página Inicial</a>
                </div>
            </div>
        </div>
    </main>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
