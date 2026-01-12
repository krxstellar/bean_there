@extends('layouts.admin')

@section('admin-content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="font-family: 'Cooper Black', serif; font-size: 2.5rem; color: #4A2C2A; margin: 0;">User Management</h1>
        <button onclick="openUserModal()" style="background-color: #AEA9A0; color: white; border: none; padding: 12px 24px; border-radius: 12px; font-family: 'Poppins', sans-serif; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-user-plus"></i> Add Staff
        </button>
    </div>

    <div style="background: white; border-radius: 20px; overflow: hidden; font-family: 'Poppins', sans-serif; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #F0F2F5; color: #AEA9A0; font-size: 13px; text-transform: uppercase;">
                    <th style="padding: 20px;">Staff Member</th>
                    <th style="padding: 20px;">Role</th>
                    <th style="padding: 20px;">Email</th>
                    <th style="padding: 20px;">Date Joined</th>
                    <th style="padding: 20px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody style="font-size: 14px; color: #4A2C2A;">
                @forelse($users as $user)
                <tr class="user-row" style="border-bottom: 1px solid #F0F2F5; background: {{ $loop->odd ? 'transparent' : '#FAFAFA' }};">
                    <td style="padding: 20px; display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; background: #FDF9F0; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #4A2C2A; font-weight: bold;">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                        <div>
                            <div style="font-weight: 600;">{{ $user->name }}</div>
                            <div style="font-size: 11px; color: #AEA9A0;">ID: STF-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </td>
                    <td style="padding: 20px;">
                        @php $position = $user->staff->position ?? 'Staff'; @endphp
                        <span style="background: #FFF4E5; color: #D48806; padding: 5px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">{{ $position }}</span>
                    </td>
                    <td style="padding: 20px;">{{ $user->email }}</td>
                    <td style="padding: 20px;">{{ $user->created_at->format('M d, Y') }}</td>
                    <td style="padding: 20px; text-align: center;">
                        <a href="#" onclick="openEditModal({{ $user->id }}); return false;" title="Edit" style="color: #AEA9A0; margin-right:8px;"><i class="fa-solid fa-pen-to-square"></i></a>
                        <button onclick="openDeleteModal({{ $user->id }})" style="background: none; border: none; color: #E74C3C; cursor: pointer; padding: 5px;"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:20px; text-align:center; color:#AEA9A0;">No staff members found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Edit User Modal (populated via AJAX) --}}
    <div id="editUserModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.35); z-index: 10000; backdrop-filter: blur(4px); align-items: center; justify-content: center;">
        <div style="background: white; width: 520px; border-radius: 25px; padding: 35px; position: relative; font-family: 'Poppins', sans-serif;">
            <h2 style="font-family: 'Cooper Black', serif; color: #4A2C2A; margin-top: 0;">Edit Staff Member</h2>
            <form id="editUserForm" action="" method="POST">
                @csrf
                @method('PATCH')
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Full Name</label>
                    <input id="edit_name" name="name" type="text" placeholder="Enter name" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #F0F2F5; outline: none;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Position</label>
                    <select id="edit_position" name="position" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #F0F2F5; outline: none; background: white;">
                        <option value="Baker">Baker</option>
                        <option value="Barista">Barista</option>
                        <option value="Store Manager">Store Manager</option>
                    </select>
                </div>
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Email Address</label>
                    <input id="edit_email" name="email" type="email" placeholder="email@beanthere.com" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #F0F2F5; outline: none;">
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="button" onclick="closeEditModal()" style="flex: 1; display:inline-flex; align-items:center; justify-content:center; padding: 12px; border-radius: 12px; border: none; background: #F5F5F5; cursor: pointer; font-weight: 600; font-family: 'Poppins', sans-serif;">Cancel</button>
                    <button type="submit" style="flex: 1; display:inline-flex; align-items:center; justify-content:center; padding: 12px; border-radius: 12px; border: none; background: #4A2C2A; color: white; cursor: pointer; font-weight: 600; font-family: 'Poppins', sans-serif;">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete confirmation modal --}}
    <div id="deleteUserModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.35); z-index: 10000; backdrop-filter: blur(4px); align-items: center; justify-content: center;">
        <div style="background: white; width: 420px; border-radius: 14px; padding: 22px; font-family: 'Poppins', sans-serif;">
            <h3 style="margin:0 0 8px 0; font-family: 'Cooper Black', serif; color:#4A2C2A;">Confirm Delete</h3>
            <p style="color:#4A2C2A; margin:0 0 18px 0;">Are you sure you want to delete this staff member? This action cannot be undone.</p>
            <form id="deleteUserForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div style="display:flex; gap:10px;">
                    <button type="button" onclick="closeDeleteModal()" style="flex:1; display:inline-flex; align-items:center; justify-content:center; padding:10px 12px; background:#F5F5F5; border-radius:8px; border:none; font-family:'Poppins',sans-serif;">Cancel</button>
                    <button type="submit" style="flex:1; display:inline-flex; align-items:center; justify-content:center; padding:10px 12px; background:#E74C3C; color:white; border-radius:8px; border:none; font-family:'Poppins',sans-serif;">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <div style="margin-top:12px;">{{ $users->links() }}</div>

    <div id="userModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.35); z-index: 10000; backdrop-filter: blur(4px); align-items: center; justify-content: center;">
        <div style="background: white; width: 450px; border-radius: 25px; padding: 35px; position: relative; font-family: 'Poppins', sans-serif;">
            <h2 style="font-family: 'Cooper Black', serif; color: #4A2C2A; margin-top: 0;">Add Staff Member</h2>
            <form id="createUserForm" action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div id="createFormErrors" style="color:#E74C3C; margin-bottom:12px; display:none;"></div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Full Name</label>
                    <input name="name" type="text" placeholder="Enter name" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #F0F2F5; outline: none;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Staff Role</label>
                    <select name="position" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #F0F2F5; outline: none; background: white;">
                        <option value="Baker">Baker</option>
                        <option value="Barista">Barista</option>
                        <option value="Store Manager">Store Manager</option>
                    </select>
                </div>
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Email Address</label>
                    <input name="email" type="email" placeholder="email@beanthere.com" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #F0F2F5; outline: none;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Password</label>
                    <input name="password" type="password" placeholder="Choose a password" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #F0F2F5; outline: none;">
                </div>
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Confirm Password</label>
                    <input name="password_confirmation" type="password" placeholder="Confirm password" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #F0F2F5; outline: none;">
                </div>
                <div style="display: flex; gap: 10px;">
                    <button onclick="closeUserModal()" type="button" style="flex: 1; display:inline-flex; align-items:center; justify-content:center; padding: 12px; border-radius: 12px; border: none; background: #F5F5F5; cursor: pointer; font-weight: 600; font-family: 'Poppins', sans-serif;">Cancel</button>
                    <button type="submit" style="flex: 2; display:inline-flex; align-items:center; justify-content:center; padding: 12px; border-radius: 12px; border: none; background: #4A2C2A; color: white; cursor: pointer; font-weight: 600; font-family: 'Poppins', sans-serif;">Save Staff Member</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .user-row:hover { background-color: #FDF9F0 !important; transition: 0.2s; }
        #userModal input,
        #userModal select,
        #userModal textarea,
        #editUserModal input,
        #editUserModal select,
        #editUserModal textarea {
            box-sizing: border-box;
            width: 100%;
            max-width: 100%;
        }
    </style>

    <script>
        function openUserModal() {
            var err = document.getElementById('createFormErrors');
            if (err) { err.style.display = 'none'; err.innerHTML = ''; }
            document.getElementById('userModal').style.display = 'flex';
        }
        function closeUserModal() { document.getElementById('userModal').style.display = 'none'; }
        
        // Edit modal helpers
        function openEditModal(id) {
            fetch('/admin/users/' + id)
                .then(function(res){ if (!res.ok) throw res; return res.json(); })
                .then(function(data){
                    document.getElementById('edit_name').value = data.name || '';
                    document.getElementById('edit_email').value = data.email || '';
                    var sel = document.getElementById('edit_position');
                    sel.value = data.position || '';
                    var modal = document.getElementById('editUserModal');
                    var form = document.getElementById('editUserForm');
                    form.action = '/admin/users/' + id;
                    modal.style.display = 'flex';
                }).catch(function(){ alert('Unable to load user data.'); });
        }

        function closeEditModal() { document.getElementById('editUserModal').style.display = 'none'; }

        // Delete modal helpers
        function openDeleteModal(id) {
            var modal = document.getElementById('deleteUserModal');
            var form = document.getElementById('deleteUserForm');
            form.action = '/admin/users/' + id;
            modal.style.display = 'flex';
        }

        function closeDeleteModal() { document.getElementById('deleteUserModal').style.display = 'none'; }

        // Client-side validation for add-staff form
        document.addEventListener('DOMContentLoaded', function(){
            var form = document.getElementById('createUserForm');
            if (!form) return;

            form.addEventListener('submit', function(e){
                var errors = [];
                var name = (form.querySelector('input[name="name"]') || {}).value || '';
                var email = (form.querySelector('input[name="email"]') || {}).value || '';
                var position = (form.querySelector('select[name="position"]') || {}).value || '';
                var password = (form.querySelector('input[name="password"]') || {}).value || '';
                var passwordConfirm = (form.querySelector('input[name="password_confirmation"]') || {}).value || '';

                name = name.trim(); email = email.trim();

                if (!name) errors.push('Full name is required.');
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!email || !emailRegex.test(email)) errors.push('Please enter a valid email address.');
                if (!position) errors.push('Please select a position.');
                if (!password || password.length < 8) errors.push('Password must be at least 8 characters.');
                if (password !== passwordConfirm) errors.push('Password confirmation does not match.');

                var errorsContainer = document.getElementById('createFormErrors');
                if (errors.length) {
                    e.preventDefault();
                    errorsContainer.innerHTML = '<ul style="margin:0;padding-left:18px;">' + errors.map(function(err){ return '<li>' + err + '</li>'; }).join('') + '</ul>';
                    errorsContainer.style.display = 'block';
                    errorsContainer.scrollIntoView({behavior:'smooth', block:'center'});
                    return false;
                }
            });
        });
    </script>
@endsection