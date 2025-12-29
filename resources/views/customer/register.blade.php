@extends('layouts.customer')

@section('content')
<style>
    .register-wrapper {
        background-color: #FDF9F0;
        min-height: 90vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
    }

    .register-card {
        width: 100%;
        max-width: 900px; 
        text-align: center;
    }

    .register-card h1 {
        font-family: 'Cooper Black', serif;
        font-size: 3.5rem;
        color: #4A2C2A;
        margin-bottom: 50px;
    }

    .register-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 40px;
        row-gap: 20px;
        text-align: left;
    }

    .form-group {
        margin-bottom: 15px;
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
    }

    .terms-wrapper {
        grid-column: 2; 
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        color: #4A2C2A;
    }

    .terms-wrapper input {
        width: 18px;
        height: 18px;
        accent-color: #4A2C2A;
        cursor: pointer;
    }

    .form-actions {
        margin-top: 40px;
        display: flex;
        justify-content: space-between; 
        align-items: center;
    }

    .btn-submit {
        display: inline-block;
        text-decoration: none;
        background-color: #93796C;
        color: #FDF9F0; 
        border: none;
        padding: 14px 80px;
        border-radius: 30px;
        font-family: 'Poppins', sans-serif;
        font-size: 1.2rem;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: transform 0.2s;
        text-align: center;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        background-color: #7d665a;
        color: #FDF9F0;
    }

    .login-text {
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        color: #4A2C2A;
        margin: 0;
    }

    .login-text a {
        font-weight: 600;
        text-decoration: underline;
        color: #4A2C2A;
    }

    @media (max-width: 768px) {
        .register-grid { grid-template-columns: 1fr; }
        .terms-wrapper { grid-column: 1; }
        .form-actions { flex-direction: column; gap: 20px; text-align: center; }
    }
</style>

<div class="register-wrapper">
    <div class="register-card">
        <h1>Create Account</h1>
        
        @if($errors->any())
            <div style="background:#ffe5e5;color:#c0392b;padding:12px;border-radius:8px;margin-bottom:16px;">
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="register-grid">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter Full Name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter Email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="password-container">
                        <input type="password" name="password" id="regPass" class="form-control" placeholder="Min 8 characters" required>
                        <i class="fa-regular fa-eye-slash toggle-eye" onclick="toggleVisibility('regPass', this)"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <div class="password-container">
                        <input type="password" name="password_confirmation" id="regPassConfirm" class="form-control" placeholder="Confirm password" required>
                        <i class="fa-regular fa-eye-slash toggle-eye" onclick="toggleVisibility('regPassConfirm', this)"></i>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <p class="login-text">
                    Already have an account? <a href="{{ route('login') }}">Log in here</a>
                </p>
                <button type="submit" class="btn-submit">Create Account</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleVisibility(fieldId, icon) {
        const field = document.getElementById(fieldId);
        if (field.type === "password") {
            field.type = "text";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            field.type = "password";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
</script>
@endsection