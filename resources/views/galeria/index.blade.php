@extends('layouts.portal')

@section('title')
Galeria
@endsection

@section('content')
<div class="container py-4">
    <h4 class="fs-1 fw-bold mb-6 text-black text-center">Galeria</h4>
    @hasrole('admin')
        <div class="mb-4">
            <a href="{{ route('galeria.create') }}" class="btn btn-outline-primary">
                <i class="bi bi-plus-lg"></i> Nova Mídia
            </a>
        </div>
    @endhasrole

    @if($anos->isNotEmpty())
        <!-- Filtro por Ano -->
        <div class="d-flex justify-content-center mb-4">
            <div class="input-group" style="max-width: 200px; border-radius: 10px; overflow: hidden;">
                <label class="input-group-text" for="anoSelect" style="border-radius: 10px 0 0 10px;">Ano</label>
                <select class="form-select" id="anoSelect" onchange="location = this.value;" style="border-radius: 0 10px 10px 0;">
                    <option value="{{ route('galeria.indexPublic') }}" {{ !request('ano') ? 'selected' : '' }}>
                        Todos os Anos
                    </option>
                    @foreach($anos as $ano)
                        <option value="{{ route('galeria.ano', $ano) }}" {{ request()->is("galeria/ano/$ano") ? 'selected' : '' }}>
                            {{ $ano }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    @if($midias->isEmpty())
        <p class="text-center">Não há mídias disponíveis no momento.</p>
    @else
        <!-- Grid de Mídia -->
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($midias as $midia)
                <div class="col-md-4">
                    <div class="card mb-4">
                        @if($midia->tipo == 'imagem')
                            <img src="{{ asset($midia->caminho) }}" class="card-img-top" alt="{{ $midia->titulo }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 9px; cursor: pointer;">
                        @else
                            @php
                                $videoId = '';
                                if (preg_match('/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/', $midia->caminho, $match)) {
                                    $videoId = $match[7];
                                }
                            @endphp
                            <div class="ratio ratio-16x9"  style="width: 100%; height: 200px; object-fit: cover; border-radius: 9px; overflow: hidden;">
                                <iframe 
                                    src="https://www.youtube.com/embed/{{ $videoId }}"
                                    title="{{ $midia->titulo }}"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $midia->titulo }}</h5>
                            <p class="card-text">{{ $midia->descricao }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="d-flex justify-content-center mt-4">
            {{ $midias->links() }}
        </div>
    @endif
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('.card-img-top');
        const iframes = document.querySelectorAll('iframe');

        images.forEach(image => {
            image.addEventListener('click', function() {
                const modal = document.createElement('div');
                modal.style.position = 'fixed';
                modal.style.top = '0';
                modal.style.left = '0';
                modal.style.width = '100%';
                modal.style.height = '100%';
                modal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
                modal.style.display = 'flex';
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
                modal.style.zIndex = '1000';
                modal.style.padding = '10px'; // Add padding for better mobile view

                const modalImage = document.createElement('img');
                modalImage.src = this.src;
                modalImage.style.maxWidth = '100%';
                modalImage.style.maxHeight = '100%';
                modalImage.style.borderRadius = '10px'; // Add border-radius for better aesthetics

                modal.appendChild(modalImage);

                modal.addEventListener('click', function() {
                    modal.remove();
                });

                document.body.appendChild(modal);
            });
        });

        iframes.forEach(iframe => {
            const src = iframe.src;
            iframe.addEventListener('click', function() {
                iframe.src = src;
            });
        });
    });
</script>