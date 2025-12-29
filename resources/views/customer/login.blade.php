@extends('layouts.customer')

@section('content')
<style>
    .login-wrapper {
        background-color: #FDF9F0;
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
    }

    .login-card {
        width: 100%;
        max-width: 440px;
        text-align: center;
    }

    .login-card h1 {
        font-family: 'Cooper Black', serif;
        font-size: 3.2rem;
        color: #4A2C2A;
        margin-bottom: 40px;
    }

    .form-group {
        text-align: left;
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        font-family: 'Poppins', sans-serif;
        font-size: 1.1rem;
        font-weight: 500;
        color: #4A2C2A;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 16px 20px;
        border-radius: 12px;
        border: 1px solid #d1d1d1;
        background-color: #EAEAEA;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        box-sizing: border-box;
        outline: none;
    }

    .password-container {
        position: relative;
    }

    .toggle-eye {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #4A2C2A;
        cursor: pointer;
        font-size: 1.1rem;
        z-index: 10;
    }

    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        margin-bottom: 40px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #4A2C2A;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
    }

    .btn-submit {
        background-color: #93796C;
        color: #FDF9F0; 
        border: none;
        padding: 14px 65px;
        border-radius: 30px;
        font-family: 'Poppins', sans-serif;
        font-size: 1.2rem;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: transform 0.2s;
    }

    .btn-submit:active {
        transform: scale(0.98);
    }

    .register-text {
        margin-top: 35px;
        font-family: 'Poppins', sans-serif;
        color: #4A2C2A;
    }

    .register-text a {
        font-weight: 600;
        text-decoration: underline;
        color: #4A2C2A;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <h1>Login</h1>
        
        @if($errors->any())
            <div style="background:#ffe5e5;color:#c0392b;padding:12px;border-radius:8px;margin-bottom:16px;">
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="password-container">
                    <input type="password" name="password" id="passwordField" class="form-control" placeholder="Enter password" required>
                    <i class="fa-regular fa-eye-slash toggle-eye" id="toggleEye"></i>
                </div>
            </div>
            <div class="form-footer">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>
            <button type="submit" class="btn-submit">Login</button>
        </form>
        
        <p class="register-text">
            Don't have an account yet? <a href="{{ url('/register') }}">Create an account here</a>
        </p>
    </div>
</div>

<script>
    document.getElementById('toggleEye').addEventListener('click', function () {
        const field = document.getElementById('passwordField');
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
        this.classList.toggle('fa-eye');
    });
</script>
@endsection