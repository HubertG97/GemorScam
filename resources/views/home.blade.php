@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <form action="/crypto-filter?classification_id=" method="get" class="pb-5">
            <select class="form-control" name="classification">
                @foreach($classifications as $classification)
                    <option value="{{$classification->id}}">{{$classification->classification}}</option>
                @endforeach
            </select>

            <br>
            <button class="btn-light px-3 rounded" type="submit">Search</button>
            @csrf
        </form>

        <div class="col-md-8">
            @foreach($visible_cryptos as $crypto)
                <div id="{{$crypto->id}}" class="flex flex-row bg-white rounded shadow p-3 mb-3">
                    <div style="background-image: url({{ asset('/image/logo/' . $crypto->logo_url) }}); background-position:center; background-repeat: no-repeat; background-size: contain;" class="w-3/12 h-48"></div>
                    <div class="w-7/12 p-3">
                        <div class="flex flex-row justify-between"><h3 class="">{{$crypto->name}} ({{$crypto->ticker}})</h3><p><i>by <a href="/cryptos/other/{{$crypto->user_id}}">{{$crypto->User->name}}</a></i></p></div>
                        <p class="multi-line">{{$crypto->description}}</p>
                        <div class="flex flex-row">
                            <button class="w-24 h-8 mb-1 bg-blue-500 text-white px-3 rounded mr-2" onclick="window.location.href='/cryptos/{{ $crypto->id }}/'">More info</button>
                            <button class="w-24 h-8 mb-1 bg-yellow-500 text-white px-3 rounded" onclick="window.open('{{ $crypto->website }}','_blank')">Website</button>

                        </div>

                    </div>
                    <div class="w-2/12 flex flex-col items-end justify-center">
                        <form method="post" >
                            <input class="form-control mb-4 d-none" type="text" name="crypto_id" value="{{$crypto->id}}">
                            <input type="checkbox" class="form-control mb-4 d-none" name="checker" value=1 checked="checked">
                            <button id="gem-button" @if($crypto->checkRating(Auth::id(), $crypto->id) == 1) class="w-24 h-8 mb-1 bg-green-500 text-white px-3 rounded" @elseif($crypto->checkRating(Auth::id(), $crypto->id) == 0 || $crypto->checkRating(Auth::id(), $crypto->id) == null) class="w-24 h-8 mb-1 bg-white border-solid border-2 border-green-500 text-green-500 px-3 rounded" @endif type="submit">Gem {{$crypto->RatingCount->gem_count ?? '0'}}</button>

                            @csrf
                        </form>
                        <form method="post">
                            <input class="form-control mb-4 d-none" type="text" name="crypto_id" value="{{$crypto->id}}">
                            <input type="checkbox" class="form-control mb-4 d-none" name="checker" value=0 checked="checked">
                            <button id="scam-button" @if($crypto->checkRating(Auth::id(), $crypto->id) === 0 ) class="w-24 h-8 mb-1 bg-red-500 text-white px-3 rounded" @elseif($crypto->checkRating(Auth::id(), $crypto->id) === 1 || $crypto->checkRating(Auth::id(), $crypto->id) === null)  class="w-24 h-8 mb-1 bg-white border-solid border-2 border-red-500 text-red-500 px-3 rounded" @endif type="submit">Scam {{$crypto->RatingCount->scam_count ?? '0'}}</button>
                            @csrf
                        </form>
                    </div>
                </div>

            @endforeach
                {{ $visible_cryptos->links() }}
        </div>
        <form action="/crypto-search" method="get" class="pb-5">
            <input class="form-control mb-4" type="text" name="q">
            <br>
            <button class="btn-light px-3 rounded" type="submit">Search</button>
            @csrf
        </form>
    </div>
</div>


@endsection
