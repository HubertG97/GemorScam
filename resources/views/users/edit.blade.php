@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit User</div>

                    <div class="card-body">
                        <form action="/users/{{ $user->id }}/" method="post" class="pb-5">
                            @method('PATCH')
                            <input class="form-control mb-4" type="text" name="name" placeholder="Name" value="{{$user->name}}">
                            {{ $errors->first('name') }}
                            <input class="form-control mb-4" type="text" name="email" placeholder="Email" value="{{$user->email}}">
                            {{ $errors->first('email') }}

                            <select class="form-control" name="role">
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
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
