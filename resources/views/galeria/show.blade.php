@extends('layouts.portal')

@section('title')
    Galeria
@endsection

@section('content')
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1>{{ $galeria->titulo }}</h1>
                <p class="text-muted">{{ \Carbon\Carbon::parse($galeria->data_evento)->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-5 mb-4">
                <img src="{{ asset($galeria->imagem_principal) }}" class="img-fluid rounded shadow-sm" alt="Imagem principal de {{ $galeria->titulo }}">
            </div>
            <div class="col-md-7 mb-4">
                <h4>Sobre o Evento</h4>
                <p>{{ $galeria->descricao ?? 'Nenhuma descrição fornecida.' }}</p>
            </div>
        </div>

        <hr class="my-5">

        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">Galeria de Mídia</h3>
            </div>

            @if($galeria->midias->isNotEmpty())
                @if($galeria->tipo == 'video')
                    <div class="col-12 d-flex justify-content-center">
                        <div class="ratio ratio-16x9" style="max-width: 800px;">
                            @php
                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $galeria->midias->first()->caminho, $matches);
                                $youtubeId = $matches[1] ?? null;
                            @endphp
                            @if($youtubeId)
                                <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            @else
                                <p class="text-danger">O link do YouTube fornecido é inválido.</p>
                            @endif
                        </div>
                    </div>
                @else
                    @foreach($galeria->midias as $midia)
                        <div class="col-lg-3 col-md-4 col-6 mb-4">
                            <a href="{{ asset($midia->caminho) }}" target="_blank">
                                <img src="{{ asset($midia->caminho) }}" class="img-fluid rounded shadow-sm" alt="Foto da galeria">
                            </a>
                        </div>
                    @endforeach
                @endif
            @else
                <div class="col-12 text-center">
                    <p class="text-muted">Nenhuma mídia adicional encontrada para este evento.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
