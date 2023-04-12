@extends('adminpanel::layouts.auth')

@section('content')
<main class="form-signin w-100 h-100 m-auto d-flex flex-column justify-content-center p-2 align-items-center">
    <form class="mb-2" action="{{ route('adminpanel.login') }}" method="POST">
        @csrf
        <h1 class="h3 mb-3 fw-normal">{{ __('adminpanel::login.title') }}</h1>

        <div class="form-floating mb-3"  id="emailGroup">
            <input type="email" name="email" class="form-control" id="email" required>
            <label for="email">{{ __('adminpanel::common.fields.email') }}</label>
        </div>
        <div class="form-floating mb-3" id="passwordGroup">
            <input type="password" name="password" class="form-control" id="password"  required>
            <label for="password">{{ __('adminpanel::common.fields.password') }}</label>
        </div>

        <div class="checkbox mb-3" id="rememberMeGroup">
            <label for="remember">
                <input type="checkbox" name="remember" id="remember" value="1"> {{ __('adminpanel::common.fields.remember') }}
            </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">
            <span class="signingin visually-hidden"><x-adminpanel::icon name="refresh"/> {{ __('adminpanel::login.loggingin') }}...</span>
            <span class="signin">{{ __('adminpanel::common.buttons.login') }}</span>
        </button>
    </form>
    <div class="clearfix"></div>
    @if(!$errors->isEmpty())
        @foreach($errors->all() as $err)
        <div class="alert alert-danger" role="alert">
            {{ $err }}
        </div>
        @endforeach
    @endif

</main>
@endsection

@push('end-head-styles')
<style>
html,
body {
  height: 100%;
}
</style>
@endpush

@push('end-body-scripts')

    <script>
        var btn = document.querySelector('button[type="submit"]');
        var form = document.forms[0];
        var email = document.querySelector('[name="email"]');
        var password = document.querySelector('[name="password"]');
        btn.addEventListener('click', function(ev){
            if (form.checkValidity()) {
                btn.querySelector('.signingin').className = 'signingin';
                btn.querySelector('.signin').className = 'signin hidden';
            } else {
                ev.preventDefault();
            }
        });
        email.focus();
        document.getElementById('emailGroup').classList.add("focused");

        // Focus events for email and password fields
        email.addEventListener('focusin', function(e){
            document.getElementById('emailGroup').classList.add("focused");
        });
        email.addEventListener('focusout', function(e){
            document.getElementById('emailGroup').classList.remove("focused");
        });

        password.addEventListener('focusin', function(e){
            document.getElementById('passwordGroup').classList.add("focused");
        });
        password.addEventListener('focusout', function(e){
            document.getElementById('passwordGroup').classList.remove("focused");
        });

    </script>
@endpush
