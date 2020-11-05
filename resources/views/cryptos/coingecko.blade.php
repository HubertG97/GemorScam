@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            </form>
            <div class="col-md-8">
                @foreach($data as $crypto)
                    {{$crypto->symbol}}
                    {{$crypto->name}}
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
