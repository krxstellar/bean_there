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
        // Treat `staff` as a generic user role used for access control.
        // Specific staff positions (Baker/Barista/Store Manager) are stored on the `staffs` table
        // and are not used as the `User` role for gate checks.
        $positions = ['Baker', 'Barista', 'Store Manager'];

        // Ensure the generic 'staff' role exists.
        if (! Role::where('name', 'staff')->where('guard_name', 'web')->exists()) {
            Role::create(['name' => 'staff', 'guard_name' => 'web']);
        }

        // Only include users who have the `staff` user role and a corresponding `staffs` record.
        $users = User::role('staff')->whereHas('staff')->orderByDesc('id')->paginate(20);

        // Ensure seeded Bianca is assigned the generic staff role and a Store Manager position (idempotent)
        $bianca = User::where('email', 'bianca@beanthere.com')->first();
        if ($bianca) {
            $bianca->syncRoles(['staff']);
            if ($bianca->staff) {
                $bianca->staff->update(['position' => 'Store Manager']);
            } else {
                $bianca->staff()->create([
                    'position' => 'Store Manager',
                    'staff_code' => 'STF-' . str_pad($bianca->id, 3, '0', STR_PAD_LEFT),
                    'hired_at' => now(),
                ]);
            }
        }

        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'position' => 'required|string', // this is the staff position (Baker/Barista/...)
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // create staff record linked to user (store position)
        $user->staff()->create([
            'position' => $data['position'],
            'staff_code' => 'STF-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'hired_at' => now(),
        ]);

        // ensure the generic 'staff' role exists and assign it
        Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $user->assignRole('staff');

        return redirect()->route('admin.users')->with('success', 'Staff member added.');
    }

    // edit() removed — editing is handled via AJAX modal on the users list page

    /**
     * Return JSON used to populate the edit modal on the admin users page.
     */
    public function show(User $user)
    {
        $user->load('staff');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'position' => $user->staff->position ?? null,
        ]);
    }

    // removed duplicate edit method; edit() above returns the view with $positions

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

        // ensure generic staff role remains assigned
        $user->syncRoles(['staff']);

        if ($user->staff) {
            $user->staff->update([
                'position' => $data['position'],
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'Staff updated.');
    }

    public function destroy(User $user)
    {
        // soft or hard delete according to app needs; here we delete the user (cascades to staff)
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Staff member removed.');
    }
}
