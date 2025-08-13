@extends('layouts.portal')

@section('title')
    Projetos
@endsection

@section('content')
<div id="projetos" class="pb-8" data-aos="fade-in">
    <div class="container">
        <h4 class="fs-1 fw-bold mb-6 text-black text-center">Projetos</h4>

        @if($projetos->isEmpty())
            <p class="text-center text-white">Não há projetos disponíveis no momento.</p>
        @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
    @foreach($projetos as $projeto)
    <style>
    @media (max-width: 767px) {
        .project-card {
        height: auto;
        }
    }

    @media (min-width: 768px) {
        .project-card {
        height: 580px;
        }
    }
    @media (min-width: 1200px) {
        .project-card {
        height: 660px;
        }
    }
    </style>
    <!-- Project Post-->
    <div class="col">
        <div class="d-flex flex-column rounded-4 card overflow-hidden shadow-lg position-relative hover-lift project-card" style="cursor: pointer;"
            data-bs-toggle="modal" data-bs-target="#projectModal{{ $projeto->id }}">
                @if($projeto->imagem && file_exists(public_path($projeto->imagem)))
                <img src="{{ asset($projeto->imagem) }}" alt="{{ $projeto->nome }}" class="img-fluid flex-shrink-0" style="height: 220px; object-fit: cover; width: 100%;">
                @else
                <img src="https://avatars.githubusercontent.com/u/217792933?s=200&v=4" alt="Default Image" class="img-fluid flex-shrink-0" style="height: 220px; object-fit: contain; width: 100%;">
                @endif
                     
            <div class="card-body p-4 p-lg-5 d-flex flex-column">

                <p class="card-title fw-bolder mb-4">{{ $projeto->nome }}</p>
                <p class="fw-medium fs-7 text-decoration-none link-cover text-secondary rounded">
                    {{ \Illuminate\Support\Str::limit($projeto->descricao, 160) }} <span class="text-primary fw-bold">ver mais</span>
                </p>
                <div class="mt-auto">
                    @if($projeto->tags->isNotEmpty())
                        <div class="mb-2">
                            @foreach($projeto->tags->take(4) as $tag)
                                <span class="badge rounded-pill" style="background-color: #e9ecef; color: #222;">{{ $tag->name }}</span>
                            @endforeach
                            @if($projeto->tags->count() > 4)
                                <span class="badge rounded-pill" style="background-color: #e9ecef; color: #222;">+{{ $projeto->tags->count() - 4 }}</span>
                            @endif
                        </div>
                    @else
                        <span class="text-muted fst-italic">Sem tags</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- / Project Post-->
    @endforeach
</div>
        @endif
    </div>
</div>

@foreach($projetos as $projeto)
<div class="modal fade" id="projectModal{{ $projeto->id }}" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content rounded-4">
            <div class="modal-body pt-4 px-4 pt-lg-5 px-lg-5">
                <div class="row">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h5 class="modal-title fs-4 fw-bolder mb-0" id="projectModalLabel">{{ $projeto->nome }}</h5>
                    </div>
                    <div class="col-lg-5 mb-2">
                        @if($projeto->imagem && file_exists(public_path($projeto->imagem)))
                        <div style="aspect-ratio: 16 / 9; width: 100%; overflow: hidden;">
                            <img src="{{ asset($projeto->imagem) }}" alt="{{ $projeto->nome }}" class="img-fluid rounded-4" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        @else
                        <img src="https://avatars.githubusercontent.com/u/217792933?s=200&v=4" alt="Default Image" class="img-fluid rounded-4 flex-shrink-0" style="height: 220px; object-fit: contain; width: 100%; border: 2px solid #e9ecef;">
                        @endif
                        <div class="mt-4 mb-4">
                            <p class="fw-bold mb-0">Palavras-chave</p>
                            @if($projeto->tags->isNotEmpty())
                            @foreach($projeto->tags as $tag)
                            <span class="badge rounded-pill" style="background-color: #e9ecef; color: #222;">{{ $tag->name }}</span>
                            @endforeach
                            @else
                            <span class="text-muted fst-italic">Sem tags</span>
                            @endif
                        </div>
                        <p class="fw-bold mb-0">Status</p>
                        @if($projeto->status === 'concluido')
                            <span class="bg-success" style="display: inline-block; width: 7px; height: 7px; background-color: #28a745; border-radius: 50%; margin-right: 6px; vertical-align: middle;"></span>
                            <span>Finalizado</span>
                        @else
                            <span style="display: inline-block; width: 7px; height: 7px; background-color: #FF894F; border-radius: 50%; margin-right: 6px; vertical-align: middle;"></span>
                            <span>Em andamento</span>
                        @endif
                    </div>
                    <div class="col-lg-7">
                        <p class="fw-bold mb-0">Descrição</p>
                        <p class="fw-medium fs-6 text-decoration-none link-cover text-secondary">
                            {{ $projeto->descricao }}
                        </p>                        
                    </div>
                </div>
            </div>
            <div class="modal-footer rounded-4 p-4 p-lg-5" style="padding-top: 0 !important">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="window.location.href='/contato'">Vamos conversar!</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection