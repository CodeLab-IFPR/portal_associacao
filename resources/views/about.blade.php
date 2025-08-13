@extends('layouts.portal')

<!-- Titulo -->
@section('title')
Sobre Nós
@endsection
<!-- Titulo -->

@section('content')
<header class="pt-10">
    <div class="container">
        <div class="text-center col-12 col-sm-9 col-lg-7 col-xl-6 mx-auto position-relative z-index-20">
            <h1 class="display-3 fw-bold mb-3">Sobre Nós</h1>
            @php
                $fraseSobre = \App\Models\FraseInicio::find(2)->frase ?? 'Frase não encontrada';
            @endphp
            <p class="text-muted lead mb-0">{{ $fraseSobre }}</p>
        </div>
    </div>
</header>
<div class="container position-relative z-index-20 py-7">
    <div class="row g-3">
        <!-- <div class="col-12 col-lg-6 d-none d-lg-block">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <picture>
                        <img class="img-fluid rounded shadow-sm"
                            src="{{ asset('img/about-1.jpeg') }}"
                            alt="HTML Bootstrap Template by Pixel Rocket">
                    </picture>
                </div>
                <div class="col-12 col-md-6">
                    <div class="row gy-3">
                        <div class="col-12">
                            <picture>
                                <img class="img-fluid rounded shadow-sm"
                                    src="{{ asset('img/about-2.jpeg') }}"
                                    alt="HTML Bootstrap Template by Pixel Rocket">
                            </picture>
                        </div>
                        <div class="col-12">
                            <picture>
                                <img class="img-fluid rounded shadow-sm"
                                    src="{{ asset('img/about-3.jpeg') }}"
                                    alt="HTML Bootstrap Template by Pixel Rocket">
                            </picture>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="row g-3">
                <div class="col-12 col-md-6 d-none d-lg-block">
                    <picture>
                        <img class="img-fluid rounded shadow-sm"
                            src="{{ asset('img/about-4.jpeg') }}"
                            alt="HTML Bootstrap Template by Pixel Rocket">
                    </picture>
                </div>
                <div class="col-12 col-md-6">
                    <div class="row gy-3">
                        <div class="col-12">
                            <picture>
                                <img class="img-fluid rounded shadow-sm"
                                    src="{{ asset('img/about-5.jpeg') }}"
                                    alt="HTML Bootstrap Template by Pixel Rocket">
                            </picture>
                        </div>
                        <div class="col-12">
                            <picture>
                                <img class="img-fluid rounded shadow-sm"
                                    src="{{ asset('img/about-6.jpeg') }}"
                                    alt="HTML Bootstrap Template by Pixel Rocket">
                            </picture>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-12 col-md-8 col-lg-6 mx-auto text-center py-4 border-bottom mb-5">
        <div class="my-5 d-none d-md-flex align-items-start justify-content-between">
            <div>
                <span class="display-3 fw-bold text-primary d-block">12</span>
                <span class="d-block fs-9 fw-bolder tracking-wide text-uppercase text-muted">Locations</span>
            </div>
            <div>
                <span class="display-3 fw-bold text-primary d-block">75K</span>
                <span class="d-block fs-9 fw-bolder tracking-wide text-uppercase text-muted">Customers</span>
            </div>
            <div>
                <span class="display-3 fw-bold text-primary d-block">160</span>
                <span class="d-block fs-9 fw-bolder tracking-wide text-uppercase text-muted">Staff</span>
            </div>
        </div>
    </div> -->
    <!-- <div class="py-6 row gx-8 align-items-center">
        <div class="col-12 col-lg-6">
            <p class="mb-3 small fw-bolder tracking-wider text-uppercase text-primary">How it started</p>
            <h2 class="display-5 fw-bold mb-6">Our story</h2>
            <p>In 2015, one of our founders had an experience with landing pages where he realized that you can’t
                control your results, but only act on them. This is what inspired him and the other co-founder to build
                a landing page design tool which helps users build their landing pages.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas quis tortor sed neque pellentesque
                suscipit. Quisque finibus tristique faucibus. Pellentesque rhoncus justo ac ipsum pulvinar commodo. Nam
                quis hendrerit dui. Vestibulum dolor ligula, vehicula bibendum iaculis in, placerat et sapien. Maecenas
                in odio at quam volutpat lobortis.</p>
        </div>
        <div class="col-12 col-lg-6">
            <picture>
                <img class="img-fluid rounded shadow-sm" src="{{ asset('img/about-7.jpeg') }}"
                    alt="HTML Bootstrap Template by Pixel Rocket">
            </picture>
        </div>
    </div> -->
    <div class="py-8">
    <h2 class="display-5 fw-bold mb-6 text-center">Nossa equipe</h2>
    <div class="row g-6">
        @foreach ($users as $user)
        <div class="col-12 col-md-4">
            <div class="shadow-lg hover-lift" style="position:relative; display:flex; flex-direction:column; min-width:0; word-wrap:break-word; background-color:#fff; background-clip:border-box; border-radius:0.25rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); height:100%;">
                <div style="flex:1 1 auto; padding:1rem; text-align:center;">
                    <div style="margin-bottom:1rem;">
                        <img style="width:80px; height:80px; border-radius:50%;" src="{{ asset('imagens/users/' . $user->imagem) }}" alt="{{ $user->alt }}">
                    </div>
                    <p style="font-weight:bold; margin-bottom:0.5rem; margin-top:1rem;">{{ $user->name }}</p>
                    <p style="color:#007bff; font-weight:bold; margin-bottom:1rem; font-size:small;">{{ $user->cargo }}</p>
                    <p style="color:#6c757d; margin-bottom:1rem; line-height:1.5; word-wrap:break-word; overflow-wrap:break-word; white-space:normal;">{{ $user->biografia }}</p>
                    <ul style="list-style:none; padding-left:0; display:flex; align-items:center; justify-content:center; margin-bottom:0;">
                        @if ($user->linkedin)
                        <li style="margin:0 0.5rem;"><a href="{{ $user->linkedin }}" style="text-decoration:none;" target="_blank"><i class="ri-linkedin-box-fill ri-2x"></i></a></li>
                        @endif
                        @if ($user->github)
                        <li style="margin:0 0.5rem;"><a href="{{ $user->github }}" style="text-decoration:none;" target="_blank"><i class="ri-github-fill ri-2x"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


    <div class="d-flex justify-content-center my-5">
        <div class="rounded-pill border px-5 py-3 text-muted d-flex align-items-center">
            Quer se juntar ao nosso time? <a href="{{ route('submission') }}" class="fw-bold d-flex align-items-center ms-2">Estamos esperando <i
                    class="ri-arrow-right-line ms-1"></i></a>
        </div>
    </div>
</div>
@endsection
