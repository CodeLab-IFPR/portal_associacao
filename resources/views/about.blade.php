@extends('layouts.portal')

<!-- Titulo -->
@section('title')
Sobre a AMAER
@endsection
<!-- Titulo -->

@section('content')
<header class="pt-10">
    <div class="container">
        <div class="text-center col-12 col-sm-9 col-lg-7 col-xl-6 mx-auto position-relative z-index-20">
            <h1 class="display-3 fw-bold mb-3">AMAER - Associação de Aeromodelismo e Automodelismo</h1>
            <p class="text-muted lead mb-0">Promovendo a paixão pelos modelos em escala há mais de duas décadas</p>
        </div>
    </div>
</header>

<div class="container position-relative z-index-20 py-7">
    <!-- História da Associação -->
    <div class="py-6 row gx-8 align-items-center mb-8">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="text-center mb-6">
                <p class="mb-3 small fw-bolder tracking-wider text-uppercase text-primary">Nossa História</p>
                <h2 class="display-5 fw-bold mb-4">Mais de 20 Anos de Tradição</h2>
            </div>
            
            <div class="row g-6">
                <div class="col-12 col-md-6">
                    <p class="lead">A AMAER foi fundada por entusiastas apaixonados pelos modelos em escala, unindo aeromodelistas e automodelistas em uma comunidade dedicada ao desenvolvimento técnico e ao compartilhamento de conhecimento.</p>
                    
                    <p>Ao longo dos anos, nossa associação cresceu e se consolidou como referência na região, promovendo eventos, competições e workshops que mantêm viva a tradição do modelismo.</p>
                </div>
                
                <div class="col-12 col-md-6">
                    <p>Nossa missão é fomentar o interesse pelos modelos em escala, proporcionando um ambiente de aprendizado, troca de experiências e desenvolvimento de habilidades técnicas entre nossos associados.</p>
                    
                    <p>Trabalhamos continuamente para manter um espaço seguro e organizado para a prática do aeromodelismo e automodelismo, respeitando todas as normas de segurança e regulamentações vigentes.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Atividades -->
    <div class="py-8 bg-light rounded-4 mb-8">
        <div class="container">
            <div class="text-center mb-6">
                <h2 class="display-5 fw-bold mb-4">Nossas Atividades</h2>
                <p class="lead text-muted">Descubra as diversas modalidades e eventos que promovemos</p>
            </div>
            
            <div class="row g-6">
                <div class="col-12 col-md-4">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="ri-plane-line display-4 text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Aeromodelismo</h4>
                        <p class="text-muted">Modelos de aviões rádio controlados, planadores, helicópteros e drones. Promovemos voos recreativos, treinos técnicos e competições oficiais.</p>
                    </div>
                </div>
                
                <div class="col-12 col-md-4">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="ri-car-line display-4 text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Automodelismo</h4>
                        <p class="text-muted">Carros rádio controlados de diversas categorias: pista, rally, drift e crawler. Organizamos corridas e eventos técnicos regulares.</p>
                    </div>
                </div>
                
                <div class="col-12 col-md-4">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="ri-tools-line display-4 text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Oficinas Técnicas</h4>
                        <p class="text-muted">Workshops de construção, manutenção e pilotagem. Compartilhamos conhecimento técnico e melhores práticas do modelismo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Valores e Objetivos -->
    <div class="py-6 row gx-8 align-items-center mb-8">
        <div class="col-12 col-lg-6">
            <h2 class="display-5 fw-bold mb-4">Nossos Valores</h2>
            <ul class="list-unstyled">
                <li class="mb-3">
                    <i class="ri-check-line text-primary me-3"></i>
                    <strong>Segurança:</strong> Priorizamos sempre a segurança em todas as atividades e eventos
                </li>
                <li class="mb-3">
                    <i class="ri-check-line text-primary me-3"></i>
                    <strong>Educação:</strong> Promovemos o conhecimento técnico e o desenvolvimento de habilidades
                </li>
                <li class="mb-3">
                    <i class="ri-check-line text-primary me-3"></i>
                    <strong>Comunidade:</strong> Fomentamos o espírito de camaradagem e colaboração entre membros
                </li>
                <li class="mb-3">
                    <i class="ri-check-line text-primary me-3"></i>
                    <strong>Tradição:</strong> Preservamos e transmitimos a cultura do modelismo para novas gerações
                </li>
            </ul>
        </div>
        
        <div class="col-12 col-lg-6">
            <h2 class="display-5 fw-bold mb-4">Por que fazer parte?</h2>
            <p class="mb-3">Ao se associar à AMAER, você terá acesso a:</p>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="ri-arrow-right-s-line text-primary me-2"></i>Campo de voo e pista dedicados</li>
                <li class="mb-2"><i class="ri-arrow-right-s-line text-primary me-2"></i>Eventos e competições exclusivas</li>
                <li class="mb-2"><i class="ri-arrow-right-s-line text-primary me-2"></i>Orientação técnica especializada</li>
                <li class="mb-2"><i class="ri-arrow-right-s-line text-primary me-2"></i>Rede de contatos no modelismo</li>
                <li class="mb-2"><i class="ri-arrow-right-s-line text-primary me-2"></i>Desconto em produtos e serviços</li>
                <li class="mb-2"><i class="ri-arrow-right-s-line text-primary me-2"></i>Biblioteca técnica e recursos educativos</li>
            </ul>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="col-12 col-md-10 mx-auto text-center py-6 border-top border-bottom mb-6">
        <div class="row">
            <div class="col-12 col-md-3 mb-4 mb-md-0">
                <span class="display-4 fw-bold text-primary d-block">20+</span>
                <span class="d-block fs-6 fw-bolder tracking-wide text-uppercase text-muted">Anos de História</span>
            </div>
            <div class="col-12 col-md-3 mb-4 mb-md-0">
                <span class="display-4 fw-bold text-primary d-block">150+</span>
                <span class="d-block fs-6 fw-bolder tracking-wide text-uppercase text-muted">Associados Ativos</span>
            </div>
            <div class="col-12 col-md-3 mb-4 mb-md-0">
                <span class="display-4 fw-bold text-primary d-block">50+</span>
                <span class="d-block fs-6 fw-bolder tracking-wide text-uppercase text-muted">Eventos</span>
            </div>
            <div class="col-12 col-md-3">
                <span class="display-4 fw-bold text-primary d-block">2+</span>
                <span class="d-block fs-6 fw-bolder tracking-wide text-uppercase text-muted">Modalidades Diferentes</span>
            </div>
        </div>
    </div>
    <!-- Diretoria -->
    <div class="py-8">
        <h2 class="display-5 fw-bold mb-6 text-center">Nossa Diretoria</h2>
        <div class="row g-8 gy-6">
            <!-- Presidente -->
            <div class="col-12 col-md-4 mb-4">
                <div class="shadow-lg hover-lift" style="position:relative; display:flex; flex-direction:column; min-width:0; word-wrap:break-word; background-color:#fff; background-clip:border-box; border-radius:0.25rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); height:100%;">
                    <div style="flex:1 1 auto; padding:1rem; text-align:center;">
                        <div style="margin-bottom:1rem;">
                            <div style="width:80px; height:80px; border-radius:50%; background: linear-gradient(135deg, #007bff, #0056b3); display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="ri-user-line" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <p style="font-weight:bold; margin-bottom:0.5rem; margin-top:1rem;">Carlos Alberto Silva</p>
                        <p style="color:#007bff; font-weight:bold; margin-bottom:1rem; font-size:small;">Presidente</p>
                        <p style="color:#6c757d; margin-bottom:1rem; line-height:1.5; word-wrap:break-word; overflow-wrap:break-word; white-space:normal;">Aeromodelista há 15 anos, responsável pela coordenação geral da associação e representação institucional.</p>
                        <ul style="list-style:none; padding-left:0; display:flex; align-items:center; justify-content:center; margin-bottom:0;">
                            <li style="margin:0 0.5rem;"><a href="#" style="text-decoration:none;"><i class="ri-mail-line ri-2x text-muted"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Vice-Presidente -->
            <div class="col-12 col-md-4 mb-4">
                <div class="shadow-lg hover-lift" style="position:relative; display:flex; flex-direction:column; min-width:0; word-wrap:break-word; background-color:#fff; background-clip:border-box; border-radius:0.25rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); height:100%;">
                    <div style="flex:1 1 auto; padding:1rem; text-align:center;">
                        <div style="margin-bottom:1rem;">
                            <div style="width:80px; height:80px; border-radius:50%; background: linear-gradient(135deg, #28a745, #1e7e34); display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="ri-user-line" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <p style="font-weight:bold; margin-bottom:0.5rem; margin-top:1rem;">Maria Fernanda Santos</p>
                        <p style="color:#007bff; font-weight:bold; margin-bottom:1rem; font-size:small;">Vice-Presidente</p>
                        <p style="color:#6c757d; margin-bottom:1rem; line-height:1.5; word-wrap:break-word; overflow-wrap:break-word; white-space:normal;">Especialista em automodelismo, auxilia na gestão administrativa e eventos da associação.</p>
                        <ul style="list-style:none; padding-left:0; display:flex; align-items:center; justify-content:center; margin-bottom:0;">
                            <li style="margin:0 0.5rem;"><a href="#" style="text-decoration:none;"><i class="ri-mail-line ri-2x text-muted"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Secretário -->
            <div class="col-12 col-md-4 mb-4">
                <div class="shadow-lg hover-lift" style="position:relative; display:flex; flex-direction:column; min-width:0; word-wrap:break-word; background-color:#fff; background-clip:border-box; border-radius:0.25rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); height:100%;">
                    <div style="flex:1 1 auto; padding:1rem; text-align:center;">
                        <div style="margin-bottom:1rem;">
                            <div style="width:80px; height:80px; border-radius:50%; background: linear-gradient(135deg, #ffc107, #e0a800); display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="ri-user-line" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <p style="font-weight:bold; margin-bottom:0.5rem; margin-top:1rem;">João Pedro Oliveira</p>
                        <p style="color:#007bff; font-weight:bold; margin-bottom:1rem; font-size:small;">Secretário</p>
                        <p style="color:#6c757d; margin-bottom:1rem; line-height:1.5; word-wrap:break-word; overflow-wrap:break-word; white-space:normal;">Responsável pela documentação, atas das reuniões e comunicação oficial da AMAER.</p>
                        <ul style="list-style:none; padding-left:0; display:flex; align-items:center; justify-content:center; margin-bottom:0;">
                            <li style="margin:0 0.5rem;"><a href="#" style="text-decoration:none;"><i class="ri-mail-line ri-2x text-muted"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tesoureiro -->
            <div class="col-12 col-md-4 mb-4">
                <div class="shadow-lg hover-lift" style="position:relative; display:flex; flex-direction:column; min-width:0; word-wrap:break-word; background-color:#fff; background-clip:border-box; border-radius:0.25rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); height:100%;">
                    <div style="flex:1 1 auto; padding:1rem; text-align:center;">
                        <div style="margin-bottom:1rem;">
                            <div style="width:80px; height:80px; border-radius:50%; background: linear-gradient(135deg, #dc3545, #c82333); display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="ri-user-line" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <p style="font-weight:bold; margin-bottom:0.5rem; margin-top:1rem;">Ana Paula Costa</p>
                        <p style="color:#007bff; font-weight:bold; margin-bottom:1rem; font-size:small;">Tesoureira</p>
                        <p style="color:#6c757d; margin-bottom:1rem; line-height:1.5; word-wrap:break-word; overflow-wrap:break-word; white-space:normal;">Gerencia as finanças da associação, mensalidades e investimentos em equipamentos.</p>
                        <ul style="list-style:none; padding-left:0; display:flex; align-items:center; justify-content:center; margin-bottom:0;">
                            <li style="margin:0 0.5rem;"><a href="#" style="text-decoration:none;"><i class="ri-mail-line ri-2x text-muted"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Diretor Técnico -->
            <div class="col-12 col-md-4 mb-4">
                <div class="shadow-lg hover-lift" style="position:relative; display:flex; flex-direction:column; min-width:0; word-wrap:break-word; background-color:#fff; background-clip:border-box; border-radius:0.25rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); height:100%;">
                    <div style="flex:1 1 auto; padding:1rem; text-align:center;">
                        <div style="margin-bottom:1rem;">
                            <div style="width:80px; height:80px; border-radius:50%; background: linear-gradient(135deg, #6f42c1, #5a32a3); display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="ri-user-line" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <p style="font-weight:bold; margin-bottom:0.5rem; margin-top:1rem;">Roberto Almeida</p>
                        <p style="color:#007bff; font-weight:bold; margin-bottom:1rem; font-size:small;">Diretor Técnico</p>
                        <p style="color:#6c757d; margin-bottom:1rem; line-height:1.5; word-wrap:break-word; overflow-wrap:break-word; white-space:normal;">Coordena as atividades técnicas, treinamentos e segurança nas operações de voo.</p>
                        <ul style="list-style:none; padding-left:0; display:flex; align-items:center; justify-content:center; margin-bottom:0;">
                            <li style="margin:0 0.5rem;"><a href="#" style="text-decoration:none;"><i class="ri-mail-line ri-2x text-muted"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Diretor de Eventos -->
            <div class="col-12 col-md-4 mb-4">
                <div class="shadow-lg hover-lift" style="position:relative; display:flex; flex-direction:column; min-width:0; word-wrap:break-word; background-color:#fff; background-clip:border-box; border-radius:0.25rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); height:100%;">
                    <div style="flex:1 1 auto; padding:1rem; text-align:center;">
                        <div style="margin-bottom:1rem;">
                            <div style="width:80px; height:80px; border-radius:50%; background: linear-gradient(135deg, #17a2b8, #138496); display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="ri-user-line" style="font-size: 2rem; color: white;"></i>
                            </div>
                        </div>
                        <p style="font-weight:bold; margin-bottom:0.5rem; margin-top:1rem;">Lucas Martins de Souza</p>
                        <p style="color:#007bff; font-weight:bold; margin-bottom:1rem; font-size:small;">Diretor de Eventos</p>
                        <p style="color:#6c757d; margin-bottom:1rem; line-height:1.5; word-wrap:break-word; overflow-wrap:break-word; white-space:normal;">Organiza competições, encontros e atividades recreativas para os associados.</p>
                        <ul style="list-style:none; padding-left:0; display:flex; align-items:center; justify-content:center; margin-bottom:0;">
                            <li style="margin:0 0.5rem;"><a href="#" style="text-decoration:none;"><i class="ri-mail-line ri-2x text-muted"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Localização e Contato -->
    <div class="py-8 bg-light rounded-4 mb-6">
        <div class="container">
            <div class="row g-6 align-items-center">
                <div class="col-12 col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">Venha nos Conhecer</h2>
                    <p class="lead mb-4">Nossa sede conta com instalações modernas e um campo adequado para a prática segura do aeromodelismo e automodelismo.</p>
                    
                    <div class="mb-4">
                        <h5 class="fw-bold mb-2">Horários de Funcionamento:</h5>
                        <p class="mb-1"><strong>Fins de semana:</strong> 8h às 18h</p>
                        <p class="mb-1"><strong>Feriados:</strong> 8h às 16h</p>
                        <p class="text-muted small">*Horários podem variar durante eventos especiais</p>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-2">Facilidades:</h5>
                        <ul class="list-unstyled">
                            <li class="mb-1"><i class="ri-check-line text-primary me-2"></i>Campo de voo homologado</li>
                            <li class="mb-1"><i class="ri-check-line text-primary me-2"></i>Pista para automodelos</li>
                            <li class="mb-1"><i class="ri-check-line text-primary me-2"></i>Área de montagem e manutenção</li>
                            <li class="mb-1"><i class="ri-check-line text-primary me-2"></i>Estacionamento gratuito</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-12 col-lg-6 text-center">
                    <div class="bg-white p-6 rounded-4 shadow">
                        <h4 class="fw-bold mb-4">Faça Parte da AMAER</h4>
                        <p class="text-muted mb-4">Junte-se a uma comunidade apaixonada por modelismo e descubra um mundo de possibilidades técnicas e recreativas.</p>
                        <a href="{{ route('submission') }}" class="btn btn-primary btn-lg px-4">
                            <i class="ri-user-add-line me-2"></i>Quero me Associar
                        </a>
                        <p class="text-muted small mt-3 mb-0">Processo simples e rápido de associação</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center my-5">
        <div class="rounded-pill border px-5 py-3 text-muted d-flex align-items-center">
            Tem alguma dúvida? <a href="{{ route('contact') }}" class="fw-bold d-flex align-items-center ms-2">Entre em contato <i
                    class="ri-arrow-right-line ms-1"></i></a>
        </div>
    </div>
</div>
@endsection
