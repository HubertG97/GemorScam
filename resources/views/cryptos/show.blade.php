@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="flex flex-row bg-white rounded shadow p-3 mb-3">
                    <div style="background-image: url({{ asset('image/logo/' . $crypto->logo_url) }}); background-position:center; background-repeat: no-repeat; background-size: contain;" class="w-3/12 h-48"></div>
                    <div class="w-7/12 p-3">
                        <div class="flex flex-row justify-between"><h3 class="">{{$crypto->name}} ({{$crypto->ticker}})</h3><p><i>by {{$crypto->User->name}}</i></p></div>
                        <p class="">{{$crypto->description}}</p>
                        <p class=""><strong>Price:</strong> ${{$crypto->price}} </p>
                        <p><strong>Classification:</strong> {{$crypto->classification->classification}}</p>
                        <div class="flex flex-row">
                            <button class="w-24 h-8 mb-1 bg-yellow-500 text-white px-3 rounded" onclick="window.open('https://{{ $crypto->website }}','_blank')">Website</button>
                            <div class="flex flex-row ml-4 self-center">
                                <p class="mr-2">Gem: {{$crypto->RatingCount->gem_count ?? '0'}}</p>
                                <p>Scam: {{$crypto->RatingCount->scam_count ?? '0'}}</p>
                            </div>

                        </div>

                    </div>

            </div>
        </div>
    </div>
@endsection
