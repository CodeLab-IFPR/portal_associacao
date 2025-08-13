@extends('layouts.portal')

<!-- Titulo -->
@section('title')
{{ $noticia->titulo }}
@endsection
<!-- Titulo -->

@section('content')
<div class="container mt-5">
    <div class="card">
        <img src="{{ asset('imagens/noticias/' . $noticia->imagem) }}" class="card-img-top img-fluid" alt="Imagem da notícia" style="max-height: 400px; object-fit: cover;">
        <div class="card-body">
            <h1 class="card-title text-center">{{ $noticia->titulo }}</h1>
            <hr>
            <small><strong>Publicado em:</strong> {{ $noticia->created_at->format('d/m/Y H:i') }}</small>
            <br>
            <small><strong>Atualizado:</strong> {{ $noticia->updated_at->diffForHumans() }}</small>
            <div class="content mt-5" style="margin-left: 10%; margin-right: 10%;">
                {!! html_entity_decode($noticia->conteudo) !!}
            </div>
            <div class="tags mt-4">
                <h5><strong>Tags relacionadas:</strong></h5>
                <span class="badge bg-primary">{{ $noticia->categoria }}</span>
            </div>
            <div class="author mt-4">
                <small><strong>Por:</strong> {{ $noticia->autor }}</small>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a class="btn btn-primary btn-sm" href="{{ url()->previous() }}"><i class="fa fa-arrow-left"></i> Voltar</a>
            </div>
        </div>
    </div>
</div>
@endsection