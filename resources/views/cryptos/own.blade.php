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
                @foreach($all_cryptos as $crypto)
                    <div class="flex flex-row bg-white rounded shadow p-3 mb-3">
                        <div style="background-image: url({{ asset('image/logo/' . $crypto->logo_url) }}); background-position:center; background-repeat: no-repeat; background-size: contain;" class="w-3/12 h-48"></div>
                        <div class="w-7/12 p-3">
                            <div class="flex flex-row justify-between"><h3 class="">{{$crypto->name}} ({{$crypto->ticker}})</h3><p><i>by {{$crypto->User->name}}</i></p></div>
                            <p class="multi-line">{{$crypto->description}}</p>
                            <div class="flex flex-row">
                                <button class="w-24 h-8 mb-1 bg-blue-500 text-white px-3 rounded mr-2" onclick="window.location.href='/cryptos/{{ $crypto->id }}/'">More info</button>
                                <button class="w-24 h-8 mb-1 bg-yellow-500 text-white px-3 rounded" onclick="window.open('https://{{ $crypto->website }}','_blank')">Website</button>
                                <div class="flex flex-row ml-4 self-center">
                                    <p class="mr-2">Gem: {{$crypto->RatingCount->gem_count ?? '0'}}</p>
                                    <p>Scam: {{$crypto->RatingCount->scam_count ?? '0'}}</p>
                                </div>

                            </div>

                        </div>
                        <div class="w-2/12 flex items-center justify-around flex-column">
                            <button @if($crypto->visible)class="w-24 h-8 bg-green-500 text-white px-3 rounded" @else class="w-24 h-8 bg-red-500 text-white px-3 rounded" @endif class="w-24 h-8 bg-green-500 text-white px-3 rounded">@if($crypto->visible)Visible @else Pending @endif</button>
                            <button class="text-center w-24 h-8 bg-blue-500 text-white px-3 rounded" onclick="window.location.href='/cryptos/{{ $crypto->id }}/edit'">Edit</button>
                            <button class="text-center w-24 h-8 bg-red-500 text-white px-3 rounded" onclick="window.location.href='/cryptos/{{ $crypto->id }}/delete'">Delete</button>
                        </div>
                    </div>

                @endforeach

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
