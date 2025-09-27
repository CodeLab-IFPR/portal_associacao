@extends('layouts.portal')

@section('title')
    Galeria
@endsection

@section('content')
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1>{{ $galeria->titulo }}</h1>
                <p class="text-muted">
                    {{ \Carbon\Carbon::parse($galeria->data_inicio_evento)->format('d/m/Y') }}
                    @if($galeria->data_fim_evento)
                        - {{ \Carbon\Carbon::parse($galeria->data_fim_evento)->format('d/m/Y') }}
                    @endif
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-5 mb-4">
                @if($galeria->tipo == 'imagem')
                    <img src="{{ asset($galeria->caminho) }}" class="img-fluid rounded shadow-sm" alt="Imagem principal de {{ $galeria->titulo }}">
                @elseif($galeria->tipo == 'video')
                    @php
                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $galeria->caminho, $matches);
                        $youtubeId = $matches[1] ?? null;
                    @endphp
                    @if($youtubeId)
                        <div class="ratio ratio-16x9 rounded shadow-sm" style="max-width: 800px; margin: 0 auto;">
                            <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @else
                        <p class="text-danger text-center">O link do YouTube fornecido é inválido para a mídia principal.</p>
                    @endif
                @endif
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
                @foreach($galeria->midias as $midia)
                    @if($midia->tipo == 'imagem')
                        <div class="col-lg-3 col-md-4 col-6 mb-4">
                            <a href="{{ asset($midia->caminho) }}" target="_blank">
                                <img src="{{ asset($midia->caminho) }}" class="img-fluid rounded shadow-sm" alt="Foto da galeria">
                            </a>
                        </div>
                    @elseif($midia->tipo == 'video')
                        {{-- Se houverem mídias do tipo vídeo na GaleriaMidia --}}
                        <div class="col-12 d-flex justify-content-center mb-4">
                            <div class="ratio ratio-16x9" style="max-width: 800px;">
                                @php
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $midia->caminho, $matches);
                                    $youtubeId = $matches[1] ?? null;
                                @endphp
                                @if($youtubeId)
                                    <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @else
                                    <p class="text-danger text-center">O link do YouTube fornecido para mídia adicional é inválido.</p>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p class="text-muted">Nenhuma mídia adicional encontrada para este evento.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
