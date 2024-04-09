@extends('layouts.mail')

@section('title', 'Welcome Email')

@section('content')
    <div class="text-center bg-white mx-auto" style="width: 400px;margin: 10px auto 12px auto; padding: 10px">
        <h1 class="text-3xl mb-3">You were invited by: {{ $sender_name }}</h1>
        <p class="mb-3">To confirm your invitation please <a class="underline"
                href="{{ route('register', ['token' => $token], true) }}">register</a> to our platform.</p>
    </div>
@endsection
