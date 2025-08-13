@extends('layouts.portal')

@section('title')
    Notícias
@endsection

@section('content')
<div id="noticias">
    <div class="container">
        <h4 class="fs-1 fw-bold mb-6 text-black text-center">Notícias</h4>

        @if($noticias->isEmpty())
            <p class="text-center">Não há notícias disponíveis no momento.</p>
        @else
            <div class="row g-5">
                @foreach($noticias as $noticia)
                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="{{ route('noticias.show', ['noticia' => $noticia->id]) }}" class="text-decoration-none">
                        <div class="d-flex h-100 bg-white rounded-4 card overflow-hidden shadow-lg position-relative hover-lift">
                            <picture>
                                <img class="img-fluid" src="{{ asset('imagens/noticias/' . $noticia->imagem) }}" alt="{{ $noticia->alt }}">
                            </picture>

                            <div class="card-body p-4 p-lg-5">
                                <p class="card-title fw-medium mb-4">{{ $noticia->titulo }}</p>
                                <p class="fw-medium fs-7 text-decoration-none link-cover">Ler mais... &rarr;</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                <nav>
                    <ul class="pagination">
                        {{ $noticias->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
        @endif
    </div>
</div>
@endsection