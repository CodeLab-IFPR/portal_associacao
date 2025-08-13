@extends('layouts.portal')

@section('title')
Submissão de Demandas
@endsection

@section('content')
<header class="pt-10">
    <div class="container">
        <div class="text-center col-12 col-sm-9 col-lg-7 col-xl-6 mx-auto position-relative z-index-20">
            <h1 class="display-3 fw-bold mb-3">Submissão de Demandas</h1>
            <p class="text-muted lead mb-0">Preencha o formulário abaixo para enviar sua demanda para nossa equipe. Certifique-se de incluir todas as informações necessárias para um processamento eficiente.</p>
        </div>
    </div>
</header>
<div class="container position-relative z-index-20 py-7">
    <div class="row g-5">
        <div class="col-12 col-lg-8">
            <form id="submissionForm" method="POST" action="{{ route('submission.submit') }}" enctype="multipart/form-data" aria-labelledby="formTitle">
                @csrf
                <div class="row g-5">
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="name">Nome</label>
                        <input type="text" class="form-control rounded" id="name" name="name" placeholder="Seu Nome" required aria-required="true">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="email">E-mail</label>
                        <input type="email" class="form-control rounded" id="email" name="email" placeholder="seuemail@dominio.com" required aria-required="true">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="demand_description">Descrição da Demanda</label>
                        <textarea name="demand_description" id="demand_description" class="form-control rounded" style="resize: none; height: 150px;" placeholder="Detalhe sua demanda aqui..." required aria-required="true"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="expected_utility">Utilidade Esperada</label>
                        <textarea name="expected_utility" id="expected_utility" class="form-control rounded" style="resize: none; height: 100px;" placeholder="Descreva a utilidade esperada..." required aria-required="true"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="supporting_files">Arquivos de Suporte (Anexos)</label>
                        <input type="file" class="form-control rounded" id="supporting_files" name="supporting_files[]" accept=".pdf,.doc,.docx" multiple required aria-required="true">
                    </div>
                    <div class="col-12 justify-content-end d-flex">
                        <button class="btn btn-primary" type="submit">Enviar Demanda</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 col-lg-4 mt-5 mt-lg-0">
            <div class="mb-5">
                <p class="mb-4 small fw-bolder tracking-wider text-uppercase text-primary">FAQs sobre Submissão de Demandas</p>
                <ul class="list-unstyled">
                    <li class="mb-3"><strong>Qual o tempo de resposta?</strong> Geralmente respondemos em até 48 horas.</li>
                    <li class="mb-3"><strong>Posso enviar mais de um anexo?</strong> Sim, você pode enviar múltiplos arquivos.</li>
                    <li class="mb-3"><strong>Quais tipos de arquivos são suportados?</strong> Aceitamos PDFs e DOCs.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection