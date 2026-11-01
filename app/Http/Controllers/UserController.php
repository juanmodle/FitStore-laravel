<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFilterRequest;
use App\Models\User;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(UserFilterRequest $request): View
    {
        $query = User::with('roles')->orderBy('name');
        if ($request->filled('role')) {
            $query->whereHas('roles', fn ($roles) => $roles->where('name', $request->validated('role')));
        }
        if ($request->filled('registered_from')) {
            $query->whereDate('created_at', '>=', $request->validated('registered_from'));
        }
        if ($request->filled('registered_until')) {
            $query->whereDate('created_at', '<=', $request->validated('registered_until'));
        }
        if ($request->boolean('verified_only')) {
            $query->whereNotNull('email_verified_at');
        }

        return view('management.users', [
            'users' => $query->paginate(10)->withQueryString(),
            'roles' => Role::where('guard_name', 'web')->orderBy('name')->pluck('name', 'name')->all(),
        ]);
    }
}
