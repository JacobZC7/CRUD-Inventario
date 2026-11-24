<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserRoleController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->orderBy('name')
            ->paginate(15);

        return view('admin.users.index', [
            'users' => $users,
            'roles' => UserRole::cases(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        $newRole = UserRole::from($validated['role']);

        if ($request->user()->is($user) && $newRole !== UserRole::Admin) {
            return back()->withErrors([
                'role' => 'No puedes quitarte el rol de Administrador a ti mismo.',
            ]);
        }

        $user->update([
            'role' => $newRole,
        ]);

        return back()->with('status', "Rol de {$user->name} actualizado a {$newRole->label()}.");
    }
}
