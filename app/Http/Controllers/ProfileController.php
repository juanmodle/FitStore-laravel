<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('account.profile', ['user' => $request->user()->load('profile')]);
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $user = $request->user();
            $user->fill($request->safe()->only(['name', 'email', 'locale']));
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            $user->save();
            $user->profile()->updateOrCreate([], ['phone' => $request->validated('phone')]);
        });

        return redirect()->route('account.profile.edit')->with('status', 'Perfil actualizado.');
    }

    public function editPassword(): View
    {
        return view('account.password');
    }

    public function password(PasswordRequest $request): RedirectResponse
    {
        $password = $request->validated('password');
        $request->user()->update(['password' => $password]);
        Auth::logoutOtherDevices($password);
        $request->session()->regenerate();

        return redirect()->route('account.profile.edit')->with('status', 'Contraseña actualizada.');
    }
}
