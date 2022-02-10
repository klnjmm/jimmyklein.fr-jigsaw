---
title: About
description: A little bit about the site
---
@extends('_layouts.main')

@section('body')
    <div class="px-4 mb-6 container mx-auto">
        <p class="mb-2">
            <img src="/assets/img/jimmy-bio.jpg" title="Jimmy Klein" alt="Jimmy Klein" class="h-full w-full" />
        </p>
        <h1 class="text-3xl mb-2">Ma bio</h1>
        <ul>
            <li class="text-gray-800 mb-2 text-lg pl-4">Marié et père de deux filles extraordinaires</li>
            <li class="text-gray-800 mb-2 text-lg pl-4">Lead développeur <a href="https://www.coservit.com" class="underline">@coservit</a></li>
            <li class="text-gray-800 mb-2 text-lg pl-4">PHP et React lover</li>
            <li class="text-gray-800 mb-2 text-lg pl-4">Je vis près de Lyon</li>
            <li class="text-gray-800 mb-2 text-lg pl-4">Télétravailleur 4 jours sur 5</li>
            <li class="text-gray-800 mb-2 text-lg pl-4">Je participe au pôle outil de l'<a href="https://www.afup.org" class="underline">AFUP</a></li>
            <li class="text-gray-800 mb-2 text-lg pl-4"><a href={{ $page['links']['twitter'] }} class="underline">Twitter</a> et <a href={{ $page['links']['youtube'] }} class="underline">Youtube</a> addict</li>
            <li class="text-gray-800 mb-2 text-lg pl-4">Guitariste dans <a href="http://www.narvalband.com" class="underline">Narval</a></li>
            <li class="text-gray-800 mb-2 text-lg pl-4">Groupe préféré : <a href="https://www.youtube.com/watch?v=NeQM1c-XCDc" class="underline">Rammstein</a></li>
            <li class="text-gray-800 mb-2 text-lg pl-4">Le pays que j'ai préféré visité : le Japon</li>
            <li class="text-gray-800 mb-2 text-lg pl-4">Série préférée : Dexter</li>
        </ul>
        <hr class="border class=text-gray-800 border-gray-400 my-6" />
        <h1 class="text-3xl mb-2">Les créations que j'apprécie</h1>
        <p class="text-gray-800">J'aime découvrir de nouvelles choses, que ce soit dans le domaine du développement web, du développement personnel, de la vidéo et de l'entreprenariat.</p>
        <h3 class="text-2xl mb-4 mt-8 flex items-center"><FaPodcast class="inline mr-1 text-gray-700"/>Podcasts</h3>
        <ul>
            <li class="mb-6">
                <a href="https://www.theminimalists.com/podcast/">
                    <p class="text-xl underline text-gray-900 hover:text-gray-700">The minimalists</p>
                    <p class="text-gray-700">Joshua Fields Millburn & Ryan Nicodemus (en anglais).</p>
                </a>
            </li>
            <li class="mb-6">
                <a href="http://www.fullstackradio.com">
                    <p class="text-xl underline text-gray-900 hover:text-gray-700">Full Stack Radio</p>
                    <p class="text-gray-700">Adam Wathan pour les développeurs (en anglais).</p>
                </a>
            </li>
            <li class="mb-6">
                <a href="https://www.generationxx.fr">
                    <p class="text-xl underline text-gray-900 hover:text-gray-700">GenerationXX</p>
                    <p class="text-gray-700">Siham Jibril interview des femmes qui entreprennent.</p>
                </a>
            </li>
        </ul>

        <h3 class="text-2xl mb-4 mt-10 flex items-center"><FaYoutube class="inline mr-1 text-gray-700"/>Youtube</h3>
        <ul>
            <li class="mb-6">
                <a href="https://www.youtube.com/channel/UCb-D560WkMEPE7dwNta_nqA">
                    <p class="text-xl underline text-gray-900 hover:text-gray-700">AFUP PHP</p>
                    <p class="text-gray-700">Conférences sur le développement Web, le PHP, le DevOps...</p>
                </a>
            </li>
            <li class="mb-6">
                <a href="https://www.youtube.com/channel/UCJ24N4O0bP7LGLBDvye7oCA">
                    <p class="text-xl underline text-gray-900 hover:text-gray-700">Matt D'Avella</p>
                    <p class="text-gray-700">Minimalisme, création de film et expériences (en anglais).</p>
                </a>
            </li>
            <li class="mb-6">
                <a href="https://www.youtube.com/channel/UCFIRm1Fv1VC4DZxmYyvNOTQ">
                    <p class="text-xl underline text-gray-900 hover:text-gray-700">Jeven Dovey</p>
                    <p class="text-gray-700">Tutoriels et revues sur la création de films, matériels et sur Youtube (en anglais).</p>
                </a>
            </li>
        </ul>
        <hr class="border border-gray-400 my-6" />

        <h1 class="text-3xl mb-2">Entrez en contact</h1>
        <p class="text-gray-800">Si vous voulez me contacter, vous pouvez me retrouver facilement sur <a href={{ $page['links']['twitter'] }} class="underline">Twitter</a>.<br/>
            J'ai ouvert une <a href={{ $page['links']['youtube'] }} class="underline">chaîne Youtube</a> où vous pouvez me retrouver et commenter mes vidéos.</p>
    </div>
@endsection
