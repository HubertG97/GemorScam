@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            </form>
            <div class="col-md-8">
                @foreach($response as $crypto)
                    <div>
                        <p>{{$crypto['current_price']}}</p>
                        <p>{{$crypto['symbol']}}</p>
                        <p>{{$crypto['market_cap']}}</p>
                        <p>{{$crypto['image']}}</p>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
@endsection
