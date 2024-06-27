@extends('auth.app')
@section('content')
<div class="text-center w-75 m-auto">
    <h4 class="text-dark-50 text-center mt-0">Reset Password</h4>
    <p class="text-muted mb-4">Enter your email address and we'll send you an email with instructions to reset your password.</p>
</div>

<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="emailaddress" class="form-label">Email address</label>
        <input class="form-control" type="email" id="emailaddress" required="" placeholder="Enter your email">
    </div>

    <div class="mb-0 text-center">
        <button class="btn btn-primary" type="submit">Reset Password</button>
    </div>
</form>
@endsection
