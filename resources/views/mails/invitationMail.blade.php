@component('vendor.mail.html.message')
    <div class="" style="width: 600px; padding: 10px">
        <h1 style="text-align: center">You were invited to Elder Watch by: {{ $sender_name }}</h1>
        <p style="text-align: center;padding-top: 10px">To activate your account, press the button below to continue your
            registration process.</p>
    </div>
    @component('vendor.mail.html.button', [
        'url' => route('register', ['token' => $token], true),
        'color' => 'rgb(82 173 94)',
    ])
        Register
    @endcomponent
@endcomponent
