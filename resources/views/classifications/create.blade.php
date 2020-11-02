@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add a cryptocurrency</div>

                    <div class="card-body">
                        <form action="" method="post" class="pb-5">
                            <input class="form-control mb-4" type="text" name="classification" placeholder="Classification">
                            {{ $errors->first('classification') }}



                            <textarea class="form-control mb-4" rows="3" name="description" placeholder="Description"></textarea>
                            {{ $errors->first('description') }}


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
