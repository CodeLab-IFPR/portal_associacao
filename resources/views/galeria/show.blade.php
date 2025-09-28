@extends('layouts.portal')

@section('title')
    {{ $galeria->titulo }} - AMAER
@endsection

@section('content')
    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="display-4">{{ $galeria->titulo }}</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6 mb-4 mb-md-0">
                <img src="{{ asset($galeria->caminho) }}" class="img-fluid rounded shadow-lg" alt="Imagem principal do evento: {{ $galeria->titulo }}">
            </div>

            <div class="col-lg-5 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h4 class="card-title border-bottom pb-2 mb-3">Detalhes do Evento</h4>

                        <div class="mb-3">
                            <strong><i class="fas fa-calendar-alt me-2"></i>Data:</strong>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($galeria->data_inicio_evento)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($galeria->data_fim_evento)->format('d/m/Y') }}</p>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-map-marker-alt me-2"></i>Descrição:</strong>
                            <p class="lead text-muted">{{ $galeria->descricao }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div class="row">
            <div class="col-12 text-center mb-4">
                <h3>Outras mídias sobre o evento:</h3>
            </div>

            @forelse($galeria->galerias as $midia)
                @if($midia->tipo == 'imagem')
                    <div class="col-lg-3 col-md-4 col-6 mb-4">
                        <a href="{{ asset($midia->caminho) }}" target="_blank">
                            <img src="{{ asset($midia->caminho) }}" class="img-fluid rounded shadow-sm" alt="Foto da galeria">
                        </a>
                    </div>
                @elseif($midia->tipo == 'video')
                    <div class="col-12 d-flex justify-content-center mb-4">
                        <div class="ratio ratio-16x9" style="max-width: 800px;">
                            @php
                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $midia->caminho, $matches);
                                $youtubeId = $matches[1] ?? null;
                            @endphp
                            @if($youtubeId)
                                <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            @else
                                <p class="text-danger text-center">Link do YouTube inválido.</p>
                            @endif
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Nenhuma mídia adicional encontrada para este evento.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush
