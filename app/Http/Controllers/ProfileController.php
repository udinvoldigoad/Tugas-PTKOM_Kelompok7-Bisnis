<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'totalPenjualanHariIni' => Transaksi::totalPenjualanHariIni(userId: $request->user()->id),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $previousAvatar = $user->avatar;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        if (! empty($validated['full_name']) && empty($validated['name'])) {
            $parts = explode(' ', trim($validated['full_name']));
            $validated['name'] = $parts[0] ?? $validated['full_name'];
        }

        $user->fill($validated);
        $user->save();

        if (isset($validated['avatar']) && $previousAvatar && $previousAvatar !== $validated['avatar']) {
            Storage::disk('public')->delete($previousAvatar);
        }

        return redirect()->back(fallback: route('profile.edit'))->with('status', 'profile-updated');
    }

    /**
     * Remove the user's avatar.
     */
    public function destroyAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
            $user->save();
        }

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->transaksis()->exists()) {
            return redirect()->back()->withErrors([
                'password' => 'Akun yang memiliki riwayat transaksi tidak dapat dihapus.',
            ], 'userDeletion');
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
