@extends('layouts.admin')

@section('admin-content')
    <h1 style="font-family: 'Cooper Black', serif; font-size: 2.5rem; color: #4A2C2A; margin-bottom: 25px;">Settings</h1>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; font-family: 'Poppins', sans-serif;">
        
        <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <h3 style="margin-top: 0; color: #4A2C2A; border-bottom: 1.5px solid #F0F2F5; padding-bottom: 10px;">Store Profile</h3>
            
            @if(session('status'))
                <div style="background:#E6FBF1; border:1px solid #A6E2C6; color:#1E6F41; padding:12px 16px; border-radius:10px; margin-bottom:16px; font-weight:600;">{{ session('status') }}</div>
            @endif

            <div style="margin-top: 20px;">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $settings['email'] ?? 'support@beanthere.com') }}" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #F0F2F5; outline: none; box-sizing: border-box; max-width:100%;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $settings['contact_number'] ?? '0987 654 3210') }}" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #F0F2F5; outline: none; box-sizing: border-box; max-width:100%;">
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Store Address</label>
                    <textarea name="store_address" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #F0F2F5; outline: none; height: 80px; box-sizing: border-box; max-width:100%;">{{ old('store_address', $settings['store_address'] ?? 'Quezon City, Metro Manila') }}</textarea>
                </div>

                <button type="submit" style="background: #4A2C2A; color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 600; cursor: pointer;">Save Profile</button>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 30px;">
            
            <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <h3 style="margin-top: 0; color: #4A2C2A; border-bottom: 1.5px solid #F0F2F5; padding-bottom: 10px;">Operating Hours</h3>

                <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 14px;">Weekdays</span>
                        <input type="text" name="hours_weekdays" value="{{ old('hours_weekdays', $settings['hours_weekdays'] ?? '8:00 AM - 7:00 PM') }}" style="width: 160px; padding: 8px; border-radius: 8px; border: 1.5px solid #F0F2F5; text-align: center; box-sizing: border-box; max-width:100%;">
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 14px;">Weekends</span>
                        <input type="text" name="hours_weekend" value="{{ old('hours_weekend', $settings['hours_weekend'] ?? '9:00 AM - 5:00 PM') }}" style="width: 160px; padding: 8px; border-radius: 8px; border: 1.5px solid #F0F2F5; text-align: center; box-sizing: border-box; max-width:100%;">
                    </div>
                </div>
            </div>

            <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <h3 style="margin-top: 0; color: #4A2C2A; border-bottom: 1.5px solid #F0F2F5; padding-bottom: 10px;">Login & Security</h3>
                <p style="font-size: 13px; color: #AEA9A0; margin-bottom: 20px;">Update your admin password periodically to stay secure.</p>
                <button type="button" id="openChangePasswordModal" style="background: #FDF9F0; color: #4A2C2A; border: 1.5px solid #4A2C2A; padding: 10px 20px; border-radius: 10px; font-weight: 600; cursor: pointer;">Change Password</button>
            </div>

        </div>
        </div>
    </form>
    
    <div id="changePasswordModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); align-items:center; justify-content:center; z-index:1200;">
        <div style="width:420px; background:white; border-radius:12px; padding:22px; box-shadow:0 8px 30px rgba(0,0,0,0.15); font-family:Poppins, sans-serif;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <h3 style="margin:0; color:#4A2C2A;">Change Password</h3>
                <button type="button" id="closeChangePasswordModal" style="background:transparent; border:none; font-size:18px; cursor:pointer;">✕</button>
            </div>

            @if($errors->has('current_password') || $errors->has('password'))
                <div style="background:#FFECEB; border:1px solid #F5B7B3; color:#7A221E; padding:10px 12px; border-radius:8px; margin-bottom:12px; font-weight:600;">
                    Please fix the errors below.
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.change_password') }}">
                @csrf
                <div style="margin-bottom:12px;">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Current Password</label>
                    <input type="password" name="current_password" value="{{ old('current_password') }}" style="width:100%; padding:10px; border-radius:8px; border:1.5px solid #F0F2F5; box-sizing:border-box;">
                    @error('current_password')<div style="color:#B00020; font-size:13px; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div style="margin-bottom:12px;">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">New Password</label>
                    <input type="password" name="password" style="width:100%; padding:10px; border-radius:8px; border:1.5px solid #F0F2F5; box-sizing:border-box;">
                    @error('password')<div style="color:#B00020; font-size:13px; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Confirm New Password</label>
                    <input type="password" name="password_confirmation" style="width:100%; padding:10px; border-radius:8px; border:1.5px solid #F0F2F5; box-sizing:border-box;">
                </div>

                <div style="display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" id="cancelChangePassword" style="background:#F0F0F0; border:none; padding:10px 14px; border-radius:8px; cursor:pointer;">Cancel</button>
                    <button type="submit" style="background:#4A2C2A; color:white; border:none; padding:10px 14px; border-radius:8px; font-weight:600; cursor:pointer;">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function(){
            var openBtn = document.getElementById('openChangePasswordModal');
            var modal = document.getElementById('changePasswordModal');
            var closeBtn = document.getElementById('closeChangePasswordModal');
            var cancelBtn = document.getElementById('cancelChangePassword');

            function showModal(){ modal.style.display = 'flex'; }
            function hideModal(){ modal.style.display = 'none'; }

            if(openBtn) openBtn.addEventListener('click', showModal);
            if(closeBtn) closeBtn.addEventListener('click', hideModal);
            if(cancelBtn) cancelBtn.addEventListener('click', hideModal);
        })();
    </script>

    @if($errors->has('current_password') || $errors->has('password'))
        <script>document.addEventListener('DOMContentLoaded', function(){ document.getElementById('changePasswordModal').style.display = 'flex'; });</script>
    @endif

@endsection