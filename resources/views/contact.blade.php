@extends('layouts.portal')

<!-- Titulo -->
@section('title')
Contate Nos
@endsection
<!-- Titulo -->

@section('content')
<header class="pt-10">
    <div class="container">
        <div class="text-center col-12 col-sm-9 col-lg-7 col-xl-6 mx-auto position-relative z-index-20">
            <h1 class="display-3 fw-bold mb-3">Entre em contato</h1>
            <p class="text-muted lead mb-0">Selecione uma categoria abaixo para enviar um e-mail para a equipe de suporte correta, ou, alternativamente, envie-nos uma mensagem geral usando o formulário abaixo.</p>
        </div>
    </div>
</header>
<div class="container position-relative z-index-20 py-7">
    <div class="row gx-10 my-10 pt-5">
        <div class="col-12 col-lg-8">
            <p class="mb-3 small fw-bolder tracking-wider text-uppercase text-primary">Entre em contato</p>
            <h2 class="display-5 fw-bold mb-6">Envie-nos uma mensagem</h2>
            <form id="contactForm" method="POST" action="{{ route('send-message') }}" aria-labelledby="formTitle">
                @csrf
                <div class="row g-5">
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="name">Nome</label>
                        <input type="text" class="form-control rounded" id="name" name="name" placeholder="Seu nome" required aria-required="true">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="email">E-mail</label>
                        <input type="email" class="form-control rounded" id="email" name="email" placeholder="email@email.com.br" required aria-required="true">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="telephone">Celular</label>
                        <input type="text" class="form-control rounded" id="telephone" name="telephone" placeholder="(99) 9-9999-9999" required aria-required="true">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="message">Mensagem</label>
                        <textarea name="message" id="message" class="form-control rounded" style="resize: none; height: 150px;" placeholder="Sua mensagem..." required aria-required="true"></textarea>
                    </div>
                    <div class="col-12 justify-content-end d-flex">
                        <button class="btn btn-primary rounded" type="submit">Enviar mensagem</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 col-lg-4 mt-5 mt-lg-0">
            <div class="mb-5">
                <p class="mb-4 small fw-bolder tracking-wider text-uppercase text-primary">Nos encontre online</p>
                <ul class="list-unstyled">
                    <li class="d-flex align-items-center mb-2"><i class="ri-github-line me-3 ri-lg"></i> <a class="text-muted" href="https://github.com/CodeLab-IFPR" target="_blank">GitHub</a></li>
                </ul>
            </div>
            <p class="mb-4 small fw-bolder tracking-wider text-uppercase text-primary">Nosso endereço</p>
            <p>Av. José Felipe Tequinha, 1400 - Jardim das Nacoes, Paranavaí - PR</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var telephoneInput = document.getElementById('telephone');
        telephoneInput.addEventListener('input', function (e) {
            var x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,1})(\d{0,4})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '') + (x[4] ? '-' + x[4] : '');
        });
    });
</script>
@endsection