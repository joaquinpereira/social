<div class="card bg-light shadow-sm">
    <img src="{{$user->avatar}}" alt="{{$user->name}}" class="card-img-top">
    <div class="card-body">
        @if(auth()->id() === $user->id)
            <h5 class="card-title"><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a> <small>Eres tú</small></h5>
        @else
            <h5 class="card-title"><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></h5>
            <friendship-btn
                class="btn btn-primary w-100"
                :recipient="{{ $user }}"
            ></friendship-btn>
        @endif
    </div>
</div>
