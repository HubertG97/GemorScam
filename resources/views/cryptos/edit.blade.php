@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit crypto <p><a href="/cryptos/{{ $crypto->id }}/delete">Delete</a></p></div>

                    <div class="card-body">
                        <form action="/cryptos/{{ $crypto->id }}/" method="post" enctype="multipart/form-data" class="pb-5">
                            @method('PATCH')
                            <input class="form-control mb-4" type="text" name="name" placeholder="Name" value="{{$crypto->name}}">
                            {{ $errors->first('name') }}
                            <input class="form-control mb-4" type="text" name="ticker" placeholder="Ticker" value="{{$crypto->ticker}}">
                            {{ $errors->first('ticker') }}
                            <input class="form-control mb-4" type="text" name="price" placeholder="Price (0.26)" value="{{$crypto->price}}">
                            {{ $errors->first('price') }}

                            <textarea class="form-control mb-4" rows="3" name="description" placeholder="Description">{{$crypto->description}}</textarea>
                            {{ $errors->first('description') }}
                            <input class="form-control mb-4" type="text" name="website" placeholder="Website" value="{{$crypto->website}}">
                            {{ $errors->first('website') }}
                            <label>Logo</label>
                            <input class="form-control mb-4" type="file" name="image">
                            <select class="form-control" name="classification">
                                @foreach($classifications as $classification)
                                    <option value="{{$classification->id}}">{{$classification->classification}}</option>
                                @endforeach
                            </select>

                            <br>
                            <button class="btn-light px-3 rounded" type="submit">Submit</button>
                            @csrf
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
