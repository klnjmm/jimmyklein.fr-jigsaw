@extends('_layouts.main')

@php
    $page->type = 'article';
@endphp

@section('body')
    @if ($page->cover_image)
        <img src="{{ $page->cover_image }}" alt="{{ $page->title }} cover image" class="mb-2">
    @endif

    <h1 class="leading-none mb-2">{{ $page->title }}</h1>

    <p class="text-gray-700 text-xl md:mt-0">{{ $page->getDateInFrench() }}</p>

    @if ($page->categories)
        @foreach ($page->categories as $i => $category)
            <a
                href="{{ '/blog/categories/' . $category }}"
                title="View posts in {{ $category }}"
                class="inline-block bg-gray-300 hover:bg-blue-200 leading-loose tracking-wide text-gray-800 uppercase text-xs font-semibold rounded mr-4 px-3 pt-px"
            >{{ $category }}</a>
        @endforeach
    @endif

    <div class="border-b border-blue-200 mb-10 pb-4" v-pre>
        @yield('content')
    </div>

    <div class="bg-blue-100 px-4 py-4 lg:mb-4 mt-4 lg:mt-12">
        <p class="px-4 py-4">
            Tu as vu sur Twitter <span class="text-blue-700 font-bold">mes conseils et astuces sur le PHP</span> et tu aimerais les recevoir <span class="text-blue-700 font-bold">directement dans ta boîte mail</span> ?<br/>
            Ou justement tu n'as pas Twitter (ou tu ne me suis pas encore ^^) et <span class="text-blue-700 font-bold">tu aimerais toi aussi recevoir ces conseils</span> ?<br/><br/>
            Inscris-toi alors à ma dev letter pour recevoir régulièrement dans ta boîte mail <span class="text-blue-700 font-bold">mes conseils, mes nouveaux articles, des vidéos à voir, des outils à découvrir</span> et encore bien d’autres choses.
        </p>
        <a href="http://bit.ly/klnjmmdevletter" class="bg-blue-200 w-full block py-4 px-4 mt-4 font-bold text-blue-700 mb-4 text-center">Je m'inscris</a>
    </div>
    @include('_components.newsletter-signup')

    @if ($page->ressources !== null)
        <h1>Ressources</h1>
        <ul>
            @foreach($page->ressources as $ressource)
                <li>{{ $ressource['name'] }} <a href="{{ $ressource['link_url'] }}" title="{{ $ressource['link_name'] ?? $ressource['name'] }}">{{ $ressource['link_name'] ?? $ressource['link_url']  }}</a></li>
            @endforeach
        </ul>
    @endif

    <nav class="flex justify-between text-sm md:text-base">
        <div>
            @if ($next = $page->getNext())
                <a href="{{ $next->getUrl() }}" title="Older Post: {{ $next->title }}">
                    &LeftArrow; {{ $next->title }}
                </a>
            @endif
        </div>

        <div>
            @if ($previous = $page->getPrevious())
                <a href="{{ $previous->getUrl() }}" title="Newer Post: {{ $previous->title }}">
                    {{ $previous->title }} &RightArrow;
                </a>
            @endif
        </div>
    </nav>
@endsection
