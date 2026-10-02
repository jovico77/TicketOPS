<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('role')
            ->orderBy('name')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        $roles = Role::whereIn('name', ['User', 'Technician', 'Administrator'])
            ->orderBy('name')
            ->get();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->whereIn('name', ['User', 'Technician', 'Administrator']),
            ],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role_id' => $validated['role_id'],
            'is_active' => true,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        $roles = Role::whereIn('name', ['User', 'Technician', 'Administrator'])
            ->orderBy('name')
            ->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->whereIn('name', ['User', 'Technician', 'Administrator']),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $newRole = Role::findOrFail($validated['role_id']);
        $this->ensureAdministratorRemains($user, $newRole->name);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $newRole->id;

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('deactivate', $user);

        $this->ensureAdministratorRemains($user, $user->role->name, deactivate: true);

        $user->update(['is_active' => false]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User deactivated. Ticket and comment history has been preserved.');
    }

    public function activate(User $user): RedirectResponse
    {
        $this->authorize('activate', User::class);

        $user->update(['is_active' => true]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User reactivated successfully.');
    }

    private function ensureAdministratorRemains(
        User $user,
        string $newRole,
        bool $deactivate = false
    ): void {
        $removesActiveAdministrator = $user->is_active
            && $user->role->name === 'Administrator'
            && ($newRole !== 'Administrator' || $deactivate);

        if (
            $removesActiveAdministrator
            && User::where('is_active', true)
                ->whereHas('role', fn ($query) => $query->where('name', 'Administrator'))
                ->count() <= 1
        ) {
            throw ValidationException::withMessages([
                'role_id' => 'The last active administrator cannot be deactivated or demoted.',
            ]);
        }
    }
}
