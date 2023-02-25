<div class="card bg-light shadow-sm">
    <profile-picture-btn
        :user="{{$user}}"
    ></profile-picture-btn>

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

@push('scripts_js')
    @vite('resources/js/jquery.min.js')
    @vite('resources/js/bootstrap.bundle.min.js')
@endpush
