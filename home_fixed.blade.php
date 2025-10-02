@extends('layouts.portal')

<!-- Titulo -->
@section('title')
AMAER
@endsection
<!-- Titulo -->

@section('content')

<!-- Hero Content-->
<div class="container pt-0 pt-md-1 pt-lg-1 pb-8 pb-lg-10 position-relative">
    <div class="row align-items-center justify-content-center">

        <!-- Hero Text-->
        <div class="col-12 position-relative z-index-20 text-center" data-aos="fade-in">
            <div class="banner-container mb-4" style="max-height: 300px; overflow: hidden; border-radius: 0.5rem;">
                <img class="img-fluid w-100" src="{{ asset('img/banner_amaer.png') }}?v={{ time() }}" alt="Banner AMAER" style="object-fit: cover; height: 300px;">
            </div>
        </div>

        <div class="col-12 col-lg-8 position-relative z-index-20 text-center mx-auto" data-aos="fade-in">
            <h1 class="display-1 fw-bold mb-1">{{ $fraseInicio->titulo ?? 'AMAER' }}</h1>
            <h2 class="fs-5 fs-md-4 fw-bold mb-3">{{ $fraseInicio->subtitulo ?? 'Associação de Aeromodelismo e Automodelismo' }}</h2>
            <p class="fs-6 fs-md-5 fw-medium mb-4">{{ $fraseInicio->localizacao ?? 'Paranavaí - PR' }}</p>
            <p class="text-muted mb-5">{{ $fraseInicio->descricao ?? 'Uma associação dedicada aos amantes do aeromodelismo e automodelismo' }}</p>

            <div class="mt-4 pt-1 d-flex flex-column flex-md-row justify-content-center">
                <a href="{{ route('projeto.indexPublic') }}" class="btn btn-success" role="button">Ver Projetos</a>
                <a href="{{ route('contact') }}" class="btn btn-link text-decoration-none text-muted ms-2 bg-light-hover"
                    role="button">Entre em Contato</a>
            </div>
        </div>
        <!-- / Hero Text-->

    </div>


    <!-- Bottom left shapes-->
    <div class="position-absolute top-0 end-0 start-0 bottom-0 z-index-0 d-none d-lg-block">

        <div class="d-block f-w-6  position-absolute bottom-7 start-5 rotate-n45 origin-center">
            <span class="d-block animation-float-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 37.51 89.72">
                    <path class="text-body" d="M14.75,46.11C14.75,53,2.5,51.83,2.5,60.64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" />
                    <path class="text-body" d="M14.75,17C14.75,24,2.5,22.75,2.5,31.57" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" />
                    <path class="text-body" d="M14.75,46.11c0-6.91-12.25-5.72-12.25-14.54" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" />
                    <path class="text-body" d="M14.75,17C14.75,10.13,2.5,11.32,2.5,2.5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" />
                    <path class="text-secondary" d="M37,75.18c0,6.91-12.25,5.72-12.25,14.54" fill="none" stroke="currentColor" stroke-miterlimit="10" />
                    <path class="text-secondary" d="M37,46.11C37,53,24.76,51.83,24.76,60.64" fill="none" stroke="currentColor" stroke-miterlimit="10" />
                    <path class="text-secondary" d="M37,75.18c0-6.91-12.25-5.72-12.25-14.54" fill="none" stroke="currentColor" stroke-miterlimit="10" />
                    <path class="text-secondary" d="M37,46.11c0-6.91-12.25-5.72-12.25-14.54" fill="none" stroke="currentColor" stroke-miterlimit="10" />
                </svg> </span>
        </div>

        <div class="d-block f-w-6  position-absolute bottom-35 start-n3">
            <span class="d-block animation-float-6">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55.95 50.74">
                    <path class="text-secondary" d="M55.45,34.33A15.92,15.92,0,1,1,39.54,18.41,15.91,15.91,0,0,1,55.45,34.33Z" fill="none" stroke="currentColor" stroke-miterlimit="10" />
                    <path class="text-body" d="M34.33,18.41A15.92,15.92,0,1,1,18.41,2.5,15.92,15.92,0,0,1,34.33,18.41Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" />
                </svg> </span>
        </div>

        <div class="d-block f-w-6  position-absolute bottom-20 start-n2">
            <span class="d-block animation-float">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 62.66 58.68">
                    <polygon class="text-body" points="20.69 33.95 38.85 23.45 20.68 12.98 2.5 2.5 2.52 23.47 2.53 44.45 20.69 33.95" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" />
                    <polygon class="text-secondary" points="43.5 47.31 61.66 36.81 43.49 26.34 25.32 15.86 25.33 36.83 25.34 57.81 43.5 47.31" fill="none" stroke="currentColor" stroke-miterlimit="10" />
                </svg> </span>
        </div>
    </div>
    <!-- / Bottom left shapes-->
</div>
<!-- /hero Content-->

<div id="noticias" class="bg-primary py-8" data-aos="fade-in">
    <div class="container">
        <h4 class="fs-1 fw-bold mb-6 text-white text-center">Últimas noticias</h4>

        <div class="row g-5">
            @foreach($noticias as $noticia)
            <!-- News Post-->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="d-flex h-100 bg-white rounded-4 card overflow-hidden shadow-lg position-relative hover-lift">
                    <picture>
                        <img class="img-fluid" src="{{ asset('imagens/noticias/' . $noticia->imagem) }}" alt="{{ $noticia->alt }}">
                    </picture>

                    <div class="card-body p-4 p-lg-5">
                        <p class="card-title fw-medium mb-4">{{ $noticia->titulo }}</p>
                        <a href="{{ route('noticias.show', $noticia->id) }}" class="fw-medium fs-7 text-decoration-none link-cover">Ler mais... &rarr;</a>
                    </div>
                </div>
            </div>
            <!-- / News Post-->
            @endforeach
        </div>

        <a href="{{ route('noticias.cards') }}" class="btn btn-white mx-auto mt-7 d-table fw-medium w-100 w-md-auto">Mais Noticias &rarr;</a>
    </div>
</div>

<!-- Condições Operacionais da Pista -->
<section id="condicoes-operacionais" class="py-8 bg-light" data-aos="fade-in">
    <div class="container">
        <h4 class="fs-1 fw-bold mb-6 text-primary text-center">Condições Operacionais da Pista</h4>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        <form id="form-latlon" class="controls" autocomplete="off">
                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label for="lat" class="form-label fw-medium">Latitude</label>
                                    <input id="lat" name="lat" type="number" step="0.000001" value="-23.43013" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="lon" class="form-label fw-medium">Longitude</label>
                                    <input id="lon" name="lon" type="number" step="0.000001" value="-52.02472" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="runway" class="form-label fw-medium">Cabeceio da pista (° magnético, opcional)</label>
                                    <input id="runway" name="runway" type="number" step="1" min="0" max="360" placeholder="Ex.: 90 para Leste" class="form-control">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="d-grid w-100">
                                        <button type="submit" id="btn-atualizar" class="btn btn-primary">Atualizar</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div id="status" class="status mb-3" role="status" aria-live="polite"></div>

                        <div id="agora" class="card-weather" hidden>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0">Condições Atuais</h5>
                                <span id="last-update" class="text-muted small">—</span>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6 col-md-3">
                                    <div class="weather-item">
                                        <span class="label">Temp.</span>
                                        <strong id="now-temp" class="value">—</strong>
                                        <small class="unit">°C</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="weather-item">
                                        <span class="label">Umidade</span>
                                        <strong id="now-umid" class="value">—</strong>
                                        <small class="unit">%</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="weather-item">
                                        <span class="label">Vento</span>
                                        <strong id="now-wind" class="value">—</strong>
                                        <small class="unit">km/h</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="weather-item">
                                        <span class="label">Rajadas</span>
                                        <strong id="now-gust" class="value">—</strong>
                                        <small class="unit">km/h</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="weather-item">
                                        <span class="label">Direção</span>
                                        <strong id="now-dir" class="value">—</strong>
                                        <small class="unit">°</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="weather-item">
                                        <span class="label">Chuva</span>
                                        <strong id="now-rain" class="value">—</strong>
                                        <small class="unit">mm/h</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="weather-item">
                                        <span class="label">Nuvens</span>
                                        <strong id="now-cloud" class="value">—</strong>
                                        <small class="unit">%</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="weather-item">
                                        <span class="label">UV</span>
                                        <strong id="now-uv" class="value">—</strong>
                                    </div>
                                </div>
                            </div>

                            <div id="runway-block" class="runway-info mb-4" hidden>
                                <h6 class="fw-bold">Componentes de Vento (pista <span id="runway-hdg">—</span>°)</h6>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="weather-item">
                                            <span class="label">Head/Tail</span>
                                            <strong id="now-headtail" class="value">—</strong>
                                            <small class="unit">km/h</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="weather-item">
                                            <span class="label">Crosswind</span>
                                            <strong id="now-cross" class="value">—</strong>
                                            <small class="unit">km/h</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="conditions-alerts">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="alert-card">
                                            <h6 class="fw-bold mb-2">🛩️ Aeromodelismo</h6>
                                            <span id="badge-aereo" class="badge mb-2">—</span>
                                            <p id="msg-aereo" class="mb-0 small"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert-card">
                                            <h6 class="fw-bold mb-2">🏎️ Automodelismo</h6>
                                            <span id="badge-pista" class="badge mb-2">—</span>
                                            <p id="msg-pista" class="mb-0 small"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="previsao" class="forecast-section mt-4" hidden>
                            <h5 class="fw-bold mb-3">Próximos 5 dias</h5>
                            <div id="previsao-grid" class="forecast-grid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Localização-->
<div class="bg-dark py-8" data-aos="fade-in">
    <div class="container py-4">
        <h3 class="text-white text-center mt-3 fs-1 mb-3 fw-bold">Nossa Localização</h3>
        <p class="text-white fs-6 fs-md-5 text-center">Encontre-nos no mapa e venha conhecer nossa sede em Paranavaí - PR</p>

        <div class="row justify-content-center mt-8">
            <div class="col-12 col-lg-10">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div id="map-container" style="height: 500px; position: relative; cursor: pointer;" onclick="openGoogleMaps()">
                        <!-- Static Map Image -->
                        <img
                            src="https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-s-l+dc3545(-52.02472,-23.43013)/-52.02472,-23.43013,15/800x500@2x?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw"
                            alt="Mapa da localização da AMAER"
                            style="width: 100%; height: 500px; object-fit: cover; border-radius: 0.75rem 0.75rem 0 0;"
                            onerror="this.style.display='none'; document.getElementById('fallback-map').style.display='block';">

                        <!-- Fallback for when image fails -->
                        <div id="fallback-map" style="display: none; height: 500px; background: linear-gradient(45deg, #e8f5e8 25%, transparent 25%), linear-gradient(-45deg, #e8f5e8 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #e8f5e8 75%), linear-gradient(-45deg, transparent 75%, #e8f5e8 75%); background-size: 20px 20px; background-position: 0 0, 0 10px, 10px -10px, -10px 0px; position: relative;">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="text-center p-4">
                                    <div class="mb-4">
                                        <i class="ri-map-pin-fill text-danger" style="font-size: 60px;"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">AMAER - Maringá</h5>
                                    <p class="text-muted mb-3">Associação de Aeromodelismo e Automodelismo</p>
                                    <p class="mb-4"><strong>Coordenadas:</strong><br>
                                        Latitude: -23.43013<br>
                                        Longitude: -52.02472</p>
                                    <div class="btn btn-primary">
                                        <i class="ri-external-link-line me-1"></i>Clique para abrir no Google Maps
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Overlay with click instruction -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end justify-content-center" style="background: linear-gradient(transparent 70%, rgba(0,0,0,0.3)); pointer-events: none;">
                            <div class="text-white text-center mb-3">
                                <i class="ri-cursor-line me-1"></i>
                                <small>Clique para abrir no Google Maps</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-bold mb-2">AMAER - Maringá</h5>
                                <p class="text-muted mb-2">Associação de Aeromodelismo e Automodelismo</p>
                                <p class="mb-0"><i class="ri-map-pin-line me-2"></i>Latitude: -23.43013 | Longitude: -52.02472</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="https://www.google.com/maps?q=-23.43013,-52.02472" target="_blank" class="btn btn-primary">
                                    <i class="ri-external-link-line me-1"></i>Abrir no Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Localização-->

<!-- Como começar -->
<div class="py-5 py-lg-10 bg-light">
    <div class="container py-5 py-lg-8">
        <!-- Section Header -->
        <div class="row justify-content-center text-center mb-5">
            <div class="col-12 col-lg-8">
                <p class="mb-0 small fw-bolder tracking-wider text-uppercase text-orange">Guia para Iniciantes</p>
                <h2 class="fs-1 fw-bold mb-4 mt-3">Como começar</h2>
                <p class="text-muted fs-5">Guia rápido para dar os primeiros passos no aeromodelismo e no automodelismo com segurança, economia e diversão.</p>
            </div>
        </div>

        <!-- Section Content -->
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <section id="como-comecar" class="start-section">

                    <!-- Tabs Navigation -->
                    <div class="nav nav-pills justify-content-center mb-5" role="tablist" aria-label="Guia para iniciantes">
                        <button class="nav-link active" role="tab" aria-selected="true" aria-controls="tab-aero" id="tab-aero-btn" data-bs-toggle="pill" data-bs-target="#tab-aero">
                            <i class="ri-plane-line me-2"></i>Aeromodelismo
                        </button>
                        <button class="nav-link" role="tab" aria-selected="false" aria-controls="tab-auto" id="tab-auto-btn" data-bs-toggle="pill" data-bs-target="#tab-auto">
                            <i class="ri-car-line me-2"></i>Automodelismo
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content">

                        <!-- Aeromodelismo -->
                        <div id="tab-aero" class="tab-pane fade show active" role="tabpanel" aria-labelledby="tab-aero-btn">
                            <div class="row g-4">
                                <div class="col-12 col-lg-8">
                                    <div class="steps-container">
                                        <div class="step-item mb-4" data-aos="fade-up" data-aos-delay="100">
                                            <div class="step-number">1</div>
                                            <div class="step-content">
                                                <h4 class="fw-bold mb-3">Segurança primeiro</h4>
                                                <p class="text-muted">Voe apenas em áreas autorizadas do clube; mantenha distância de pessoas e veículos; use óculos de proteção; não voe sobre vias públicas.</p>
                                            </div>
                                        </div>

                                        <div class="step-item mb-4" data-aos="fade-up" data-aos-delay="200">
                                            <div class="step-number">2</div>
                                            <div class="step-content">
                                                <h4 class="fw-bold mb-3">Comece com um treinador</h4>
                                                <p class="text-muted">Prefira avião de espuma (EPO/EPP) do tipo "treinador"/asa alta, com trem de pouso robusto e, se possível, estabilização (gyro) para facilitar.</p>
                                            </div>
                                        </div>

                                        <div class="step-item mb-4" data-aos="fade-up" data-aos-delay="300">
                                            <div class="step-number">3</div>
                                            <div class="step-content">
                                                <h4 class="fw-bold mb-3">Kit inicial recomendado</h4>
                                                <ul class="list-unstyled">
                                                    <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Rádio 2.4 GHz (≥6 canais)</li>
                                                    <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Bateria LiPo 3S (ex.: 2200 mAh) + carregador balanceador</li>
                                                    <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Hélice/patins sobressalentes, fita de reparo</li>
                                                    <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Simulador de voo para treinar decolagem, circuitos e pouso</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="step-item mb-4" data-aos="fade-up" data-aos-delay="400">
                                            <div class="step-number">4</div>
                                            <div class="step-content">
                                                <h4 class="fw-bold mb-3">Onde praticar e regras básicas</h4>
                                                <p class="text-muted">Use a pista/área do clube; observe limite de altura local e sentido de tráfego; cheque vento antes de decolar; faça checklist pré-voo.</p>
                                            </div>
                                        </div>

                                        <div class="step-item mb-4" data-aos="fade-up" data-aos-delay="500">
                                            <div class="step-number">5</div>
                                            <div class="step-content">
                                                <h4 class="fw-bold mb-3">Próximos passos</h4>
                                                <p class="text-muted">Agende mentoria com instrutor; participe das oficinas do clube; registre seus progressos (toques, arremetidas, pousos completos).</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <!-- Checklist Card -->
                                    <div class="card border-0 shadow-sm mb-4" data-aos="fade-left" data-aos-delay="300">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="ri-clipboard-line me-2"></i>Checklist pré-voo</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Bateria carregada e íntegra</li>
                                                <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Hélices sem danos e bem fixadas</li>
                                                <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Comandos no sentido correto e failsafe configurado</li>
                                                <li class="mb-0"><i class="ri-check-line text-success me-2"></i>Vento dentro do seu limite; área de operação livre</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Tip Card -->
                                    <div class="alert alert-info" role="alert" data-aos="fade-left" data-aos-delay="400">
                                        <i class="ri-lightbulb-line me-2"></i>
                                        <strong>Dica:</strong> Domine decolagem reta → circuitos → toques e arremetidas → pouso. Use o simulador para "gravar" memória muscular.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Automodelismo Tab -->
                        <div id="tab-auto" class="tab-pane fade" role="tabpanel" aria-labelledby="tab-auto-btn">
                            <div class="row g-4">
                                <div class="col-12 col-lg-8">
                                    <div class="steps-container">
                                        <div class="step-item mb-4" data-aos="fade-up" data-aos-delay="100">
                                            <div class="step-number">1</div>
                                            <div class="step-content">
                                                <h4 class="fw-bold mb-3">Segurança e etiqueta de pista</h4>
                                                <p class="text-muted">Mantenha-se fora da pista quando houver carros; use tênis fechado; atenção a crianças e espectadores.</p>
                                            </div>
                                        </div>

                                        <div class="step-item mb-4" data-aos="fade-up" data-aos-delay="200">
                                            <div class="step-number">2</div>
                                            <div class="step-content">
                                                <h4 class="fw-bold mb-3">Escolha do modelo para iniciar</h4>
                                                <p class="text-muted">Modelos 1/10 elétricos (on-road touring ou off-road) têm manutenção simples e peças acessíveis.</p>
                                            </div>
                                        </div>

                                        <div class="step-item mb-4" data-aos="fade-up" data-aos-delay="300">
                                            <div class="step-number">3</div>
                                            <div class="step-content">
                                                <h4 class="fw-bold mb-3">Kit inicial recomendado</h4>
                                                <ul class="list-unstyled">
                                                    <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Rádio 2.4 GHz</li>
                                                    <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Bateria NiMH ou LiPo 2S + carregador balanceador</li>
                                                    <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Pneus sobressalentes (piso asfalto/carpete/terra)</li>
                                                    <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Ferramentas básicas (chaves, pinça, trava-rosca)</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="step-item mb-4" data-aos="fade-up" data-aos-delay="400">
                                            <div class="step-number">4</div>
                                            <div class="step-content">
                                                <h4 class="fw-bold mb-3">Setup e pista</h4>
                                                <p class="text-muted">Centre a direção/trim; ajuste relação para evitar superaquecimento; escolha pneus corretos; respeite o sentido da pista.</p>
                                            </div>
                                        </div>

                                        <div class="step-item mb-4" data-aos="fade-up" data-aos-delay="500">
                                            <div class="step-number">5</div>
                                            <div class="step-content">
                                                <h4 class="fw-bold mb-3">Próximos passos</h4>
                                                <p class="text-muted">Treine traçado constante; participe de baterias amistosas; faça manutenção básica (limpeza, rolamentos, diferencial).</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <!-- Checklist Card -->
                                    <div class="card border-0 shadow-sm mb-4" data-aos="fade-left" data-aos-delay="300">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="ri-clipboard-line me-2"></i>Checklist pré-bateria</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Bateria carregada e bem fixada</li>
                                                <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Parafusos apertados e direção centrada</li>
                                                <li class="mb-2"><i class="ri-check-line text-success me-2"></i>Pneus em bom estado e apropriados ao piso</li>
                                                <li class="mb-0"><i class="ri-check-line text-success me-2"></i>Temperatura do motor/ESC sob controle</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Tip Card -->
                                    <div class="alert alert-info" role="alert" data-aos="fade-left" data-aos-delay="400">
                                        <i class="ri-lightbulb-line me-2"></i>
                                        <strong>Dica:</strong> Velocidade vem da constância. Faça 5 voltas sem erro antes de buscar tempos mais baixos.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- FAQ Section -->
                <div class="row justify-content-center mt-5">
                    <div class="col-12 col-lg-8">
                        <h3 class="text-center mb-4">Perguntas frequentes</h3>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        Preciso de licença?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Dentro do clube, siga as regras internas e orientações de segurança. Fora dele, verifique a regulamentação local antes de voar/usar.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        Elétrico ou combustão para iniciar?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Elétrico costuma ser mais simples, limpo e econômico para começar.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        Quanto investir no início?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Varia conforme categoria e marcas. O clube pode sugerir kits de entrada bem aceitos pela comunidade.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="row justify-content-center mt-5">
                    <div class="col-12 text-center">
                        <a href="#inscricao" class="btn btn-primary me-3">Agende mentoria gratuita</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Como começar -->

<!-- Estilos customizados para a seção Como começar -->
<style>
    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .step-item:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .step-number {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .step-content h4 {
        color: #333;
        margin-bottom: 0.75rem;
    }

    .step-content p {
        margin-bottom: 0;
        line-height: 1.6;
    }

    .step-content ul li {
        padding: 0.25rem 0;
    }

    .step-content .ri-check-line {
        color: #28a745;
        font-weight: bold;
    }

    /* Melhorias nos cards laterais */
    .card-header.bg-primary {
        background: linear-gradient(135deg, #007bff, #0056b3) !important;
    }

    .alert-info {
        background-color: #e3f2fd;
        border-color: #bbdefb;
        color: #1565c0;
    }

    .alert-info .ri-lightbulb-line {
        color: #ffa000;
    }

    /* Responsividade aprimorada */
    @media (max-width: 768px) {
        .step-item {
            padding: 1rem;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }
</style>

<style>
    /* Estilos customizados para Condições Operacionais */
    .status {
        min-height: 20px;
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 8px;
        font-style: italic;
    }

    .card-weather {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        padding: 1.5rem;
    }

    .weather-item {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
        transition: transform 0.2s ease;
    }

    .weather-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .weather-item .label {
        display: block;
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .weather-item .value {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.125rem;
    }

    .weather-item .unit {
        font-size: 0.75rem;
        color: #9ca3af;
    }

    .runway-info {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 0.5rem;
        padding: 1rem;
    }

    .conditions-alerts .alert-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 1rem;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        border-radius: 50px;
        border: 1px solid #e5e7eb;
    }

    .badge.bom {
        background-color: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }

    .badge.atencao {
        background-color: #fef3c7;
        color: #92400e;
        border-color: #fed7aa;
    }

    .badge.desf {
        background-color: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
    }

    .forecast-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .day {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
        transition: transform 0.2s ease;
    }

    .day:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .day h5 {
        margin: 0 0 0.75rem 0;
        font-size: 1rem;
        color: #1f2937;
    }

    .day .meta {
        font-size: 0.875rem;
        color: #374151;
        margin-bottom: 0.25rem;
    }

    .day .meta strong {
        color: #1f2937;
    }

    @media (max-width: 768px) {
        .forecast-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .weather-item .value {
            font-size: 1.25rem;
        }
    }
</style>

<script>
    (function() {
        const el = {
            form: document.getElementById('form-latlon'),
            lat: document.getElementById('lat'),
            lon: document.getElementById('lon'),
            runway: document.getElementById('runway'),
            status: document.getElementById('status'),
            agora: document.getElementById('agora'),
            lastUpdate: document.getElementById('last-update'),
            nowTemp: document.getElementById('now-temp'),
            nowUmid: document.getElementById('now-umid'),
            nowWind: document.getElementById('now-wind'),
            nowGust: document.getElementById('now-gust'),
            nowDir: document.getElementById('now-dir'),
            nowRain: document.getElementById('now-rain'),
            nowCloud: document.getElementById('now-cloud'),
            nowUV: document.getElementById('now-uv'),
            runwayBlock: document.getElementById('runway-block'),
            runwayHdg: document.getElementById('runway-hdg'),
            nowHeadTail: document.getElementById('now-headtail'),
            nowCross: document.getElementById('now-cross'),
            badgeAereo: document.getElementById('badge-aereo'),
            msgAereo: document.getElementById('msg-aereo'),
            badgePista: document.getElementById('badge-pista'),
            msgPista: document.getElementById('msg-pista'),
            previsao: document.getElementById('previsao'),
            previsaoGrid: document.getElementById('previsao-grid')
        };

        function setStatus(msg) {
            el.status.textContent = msg;
        }

        function formatDate(d) {
            const local = new Date(d.getTime() - (d.getTimezoneOffset() * 60000));
            return local.toLocaleString('pt-BR');
        }

        function formatDateTime(isoStr) {
            if (!isoStr) return '—';
            try {
                const d = new Date(isoStr);
                return formatDate(d);
            } catch {
                return '—';
            }
        }

        function windDir(deg) {
            if (deg === null || deg === undefined || isNaN(deg)) return '—';
            const dirs = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
            const i = Math.round(deg / 22.5) % 16;
            return dirs[i] || '—';
        }

        function getCondition(wind, gust, temp, rain, cloud) {
            if (rain > 0.1 || cloud > 80) return { badge: 'desf', msg: 'Condições desfavoráveis' };
            if (wind > 20 || gust > 30 || temp < 5 || temp > 35) return { badge: 'atencao', msg: 'Atenção - condições marginais' };
            return { badge: 'bom', msg: 'Condições favoráveis' };
        }

        function windComponents(windSpeed, windDir, runwayHdg) {
            if (!windSpeed || !windDir || !runwayHdg) return { headtail: 0, cross: 0 };
            const radDiff = ((windDir - runwayHdg) % 360) * Math.PI / 180;
            const headtail = windSpeed * Math.cos(radDiff);
            const cross = Math.abs(windSpeed * Math.sin(radDiff));
            return { headtail: Math.round(headtail), cross: Math.round(cross) };
        }

        function renderAgora(data, runwayHdg) {
            if (!data || !data.current) return;
            const c = data.current;

            el.lastUpdate.textContent = formatDateTime(c.time);
            el.nowTemp.textContent = c.temperature_2m || '—';
            el.nowUmid.textContent = c.relative_humidity_2m || '—';
            el.nowWind.textContent = c.wind_speed_10m || '—';
            el.nowGust.textContent = c.wind_gusts_10m || '—';
            el.nowDir.textContent = c.wind_direction_10m ? `${c.wind_direction_10m}° ${windDir(c.wind_direction_10m)}` : '—';
            el.nowRain.textContent = c.rain || '0';
            el.nowCloud.textContent = c.cloud_cover || '—';
            el.nowUV.textContent = c.uv_index || '—';

            if (runwayHdg && c.wind_speed_10m && c.wind_direction_10m) {
                const comp = windComponents(c.wind_speed_10m, c.wind_direction_10m, runwayHdg);
                el.runwayHdg.textContent = runwayHdg;
                el.nowHeadTail.textContent = comp.headtail > 0 ? `+${comp.headtail}` : comp.headtail;
                el.nowCross.textContent = comp.cross;
                el.runwayBlock.hidden = false;
            } else {
                el.runwayBlock.hidden = true;
            }

            const aeroCondition = getCondition(c.wind_speed_10m, c.wind_gusts_10m, c.temperature_2m, c.rain, c.cloud_cover);
            el.badgeAereo.className = `badge ${aeroCondition.badge}`;
            el.badgeAereo.textContent = aeroCondition.badge.toUpperCase();
            el.msgAereo.textContent = aeroCondition.msg;

            const pistaCondition = c.rain > 0.1 ? 
                { badge: 'desf', msg: 'Pista molhada - não recomendado' } : 
                { badge: 'bom', msg: 'Pista seca - condições adequadas' };
            el.badgePista.className = `badge ${pistaCondition.badge}`;
            el.badgePista.textContent = pistaCondition.badge.toUpperCase();
            el.msgPista.textContent = pistaCondition.msg;

            el.agora.hidden = false;
        }

        function renderPrevisao(data) {
            if (!data || !data.daily) return;
            const daily = data.daily;
            
            el.previsaoGrid.innerHTML = '';
            
            for (let i = 0; i < Math.min(5, daily.time.length); i++) {
                const div = document.createElement('div');
                div.className = 'day';
                
                const date = new Date(daily.time[i]);
                const dayName = date.toLocaleDateString('pt-BR', { weekday: 'short' });
                const dayNum = date.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' });
                
                div.innerHTML = `
                    <h5>${dayName} ${dayNum}</h5>
                    <div class="meta">Temp: <strong>${daily.temperature_2m_min[i]}°C - ${daily.temperature_2m_max[i]}°C</strong></div>
                    <div class="meta">Vento: <strong>${daily.wind_speed_10m_max[i]} km/h</strong></div>
                    <div class="meta">Chuva: <strong>${daily.rain_sum[i] || 0} mm</strong></div>
                `;
                
                el.previsaoGrid.appendChild(div);
            }
            
            el.previsao.hidden = false;
        }

        async function carregar(lat, lon, runway) {
            setStatus('Carregando dados meteorológicos...');
            el.agora.hidden = true;
            el.previsao.hidden = true;

            try {
                const params = new URLSearchParams({
                    latitude: lat,
                    longitude: lon,
                    current: 'temperature_2m,relative_humidity_2m,rain,cloud_cover,wind_speed_10m,wind_direction_10m,wind_gusts_10m,uv_index',
                    daily: 'temperature_2m_max,temperature_2m_min,rain_sum,wind_speed_10m_max',
                    timezone: 'America/Sao_Paulo',
                    forecast_days: 5
                });

                const response = await fetch(`https://api.open-meteo.com/v1/forecast?${params}`);
                if (!response.ok) throw new Error('Erro na API');
                
                const data = await response.json();
                const runwayHdg = runway !== '' && !isNaN(runway) ? parseFloat(runway) : null;
                
                renderAgora(data, runwayHdg);
                renderPrevisao(data);
                setStatus('');
            } catch (e) {
                console.error(e);
                setStatus('Não foi possível carregar os dados. Tente novamente mais tarde.');
            }
        }

        // —— Eventos
        el.form.addEventListener('submit', (ev) => {
            ev.preventDefault();
            const lat = parseFloat(el.lat.value);
            const lon = parseFloat(el.lon.value);
            const rw = el.runway.value.trim() === '' ? '' : parseFloat(el.runway.value);
            if (Number.isNaN(lat) || Number.isNaN(lon)) {
                setStatus('Informe latitude e longitude válidas.');
                return;
            }
            // salvar preferências locais
            try {
                localStorage.setItem('amaer_loc', JSON.stringify({
                    lat,
                    lon,
                    rw
                }));
            } catch {}
            carregar(lat, lon, rw);
        });

        // Carregamento automático inicial com coordenadas padrão
        window.addEventListener('load', () => {
            const lat = parseFloat(el.lat.value);
            const lon = parseFloat(el.lon.value);
            const rw = el.runway.value.trim() === '' ? '' : parseFloat(el.runway.value);

            // Verifica se existem preferências salvas
            try {
                const saved = JSON.parse(localStorage.getItem('amaer_loc') || 'null');
                if (saved && typeof saved.lat === 'number' && typeof saved.lon === 'number') {
                    el.lat.value = saved.lat;
                    el.lon.value = saved.lon;
                    if (saved.rw !== undefined && saved.rw !== '') el.runway.value = saved.rw;
                    carregar(saved.lat, saved.lon, saved.rw);
                } else {
                    // Carrega com coordenadas padrão
                    carregar(lat, lon, rw);
                }
            } catch {
                // Se houver erro, carrega com coordenadas padrão
                carregar(lat, lon, rw);
            }
        });

    })();
</script>

<!-- Simple Map JavaScript -->
<script>
    function openGoogleMaps() {
        const latitude = -23.43013;
        const longitude = -52.02472;
        const url = `https://www.google.com/maps?q=${latitude},${longitude}`;
        window.open(url, '_blank');
    }
</script>

<style>
    /* Map container styles */
    #map-container {
        position: relative;
        background-color: #f8f9fa;
        height: 500px;
        width: 100%;
        border-radius: 0.75rem 0.75rem 0 0;
        overflow: hidden;
        transition: transform 0.2s ease;
    }

    #map-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    #map-container img {
        transition: transform 0.3s ease;
    }

    #map-container:hover img {
        transform: scale(1.02);
    }

    #fallback-map {
        border-radius: 0.75rem 0.75rem 0 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #map-container {
            height: 400px !important;
        }

        #map-container img {
            height: 400px !important;
        }

        #fallback-map {
            height: 400px !important;
        }
    }
</style>

@endsection
