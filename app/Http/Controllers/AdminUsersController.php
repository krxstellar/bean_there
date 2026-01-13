<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class AdminUsersController extends Controller
{
    public function index(Request $request)
    {
        $positions = ['Baker', 'Barista', 'Store Manager'];

        if (! Role::where('name', 'staff')->where('guard_name', 'web')->exists()) {
            Role::create(['name' => 'staff', 'guard_name' => 'web']);
        }

        $users = User::role('staff')->whereHas('staffs')->orderByDesc('id')->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'position' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->staffs()->create([
            'position' => $data['position'],
        ]);

        Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $user->assignRole('staff');

        return redirect()->route('admin.users')->with('success', 'Staff member added.');
    }

    public function show(User $user)
    {
        $user->load('staffs');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'position' => $user->staffs->position ?? null,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users','email')->ignore($user->id)],
            'position' => 'required|string',
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $user->syncRoles(['staff']);

        if ($user->staffs) {
            $user->staffs->update([
                'position' => $data['position'],
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'Staff updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Staff member removed.');
    }
}
