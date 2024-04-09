@extends('layouts.mail')

@section('title', 'Welcome Email')

@section('content')
    <div class="text-center bg-white mx-auto" style="width: 400px;margin: 10px auto 12px auto; padding: 10px">
        <h1 class="text-3xl mb-3">Welcome to our platform!</h1>
        <p class="mb-3">To confirm your email, press link below.</p>
        <a class="underline" href="{{ route('confirmEmail', $token, true) }}">Confirm</a>
    </div>
@endsection
