@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-light shadow-sm">
                    <img src="{{$user->avatar}}" alt="{{$user->name}}" class="card-img-top">
                    <div class="card-body">
                        @if(auth()->id() === $user->id)
                            <h5 class="card-title">{{ $user->name }} <small>Eres tú</small></h5>
                        @else|
                            <h5 class="card-title">{{ $user->name }}</h5>
                            <friendship-btn
                                class="btn btn-primary w-100"
                                id="request-friendship"
                                friendship-status="{{$friendshipStatus}}"
                                :recipient="{{ $user }}"
                            ></friendship-btn>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <status-list
                    url="{{route('users.statuses.index', $user)}}"
                ></status-list>
            </div>
        </div>
    </div>

@endsection
