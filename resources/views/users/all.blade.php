@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Created at</th>
                            <th scope="col">Post count</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($all_users as $user)
                        <tr>
                            <th scope="row">{{$user->id}}</th>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->roleName($user->role_id)}}</td>
                            <td>{{$user->created_at}}</td>
                            <td>{{$user->postCount()}}</td>
                            <td><a class="mr-1" href="/users/{{ $user->id }}/edit">Edit</a></td>
                        </tr>
                        @endforeach

                        </tbody>
                    </table>

            </div>
        </div>
    </div>
@endsection
