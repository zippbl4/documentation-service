@extends('no-name.layout')

@section('css')
    {!! $styles ?? '' !!}
@endsection

@section('js')
    {!! $scripts ?? '' !!}
@endsection

@section('body')
  <!-- Login Form -->
  <div class="login-container">
    <h1>{{ __('Login') }}</h1>
    <form action="{{ route('auth.login') }}" method="post">
      @csrf

      <div class="form-group">
        <label for="username">{{ __('E-Mail Address') }}</label>
        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

        @if ($errors->has('email'))
          <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('email') }}</strong>
          </span>
        @endif
      </div>

      <div class="form-group">
        <label for="password">{{ __('Password') }}</label>
        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

        @if ($errors->has('password'))
          <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('password') }}</strong>
          </span>
        @endif
      </div>

      <div class="form-group">
        <label class="form-check-label" for="remember">
          {{ __('Remember Me') }}
        </label>
        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">
          {{ __('Login') }}
        </button>
      </div>
    </form>

    @if (Route::has('auth.password.request'))
      <div class="forgot-password">
        <a class="btn btn-link" href="{{ route('auth.password.request') }}">
          {{ __('Forgot Your Password?') }}
        </a>
      </div>
    @endif
  </div>
@endsection
