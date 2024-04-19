@component('vendor.mail.html.message')
    <div class="" style="width: 600px; padding: 10px">
        <h1 style="text-align: center">Welcome to Elder Watch platform!</h1>
        <p style="text-align: center;padding-top: 10px">To confirm your email, press the button below.</p>
    </div>
    @component('vendor.mail.html.button', [
        'url' => route('confirmEmail', $token, true),
        'color' => 'rgb(82 173 94)',
    ])
        Activate Account
    @endcomponent
@endcomponent
