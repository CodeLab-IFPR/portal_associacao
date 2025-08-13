@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error'))

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Página não encontrada. Volte para a página inicial ou use a barra de pesquisa para encontrar o que procura.">
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
                        <strong>Oops! Algo deu errado no servidor. 😵</strong></p>
                    <p>
                        Não se preocupe, estamos trabalhando para resolver isso o mais rápido possível. 🚀</p>
                </div>
                <div class="d-flex justify-content-center gap-3"></div>
                <a href="/" class="btn btn-outline-primary">Voltar à Página Inicial</a>
            </div>
        </div>
        </div>
    </main>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
