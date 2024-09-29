<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Cloudinary;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    
    public function updateIcon(Request $request)
{
    \Log::info('updateIcon called');
    $request->validate([
        'image_path' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user = Auth::user();
    
    try {
        // Cloudinaryに画像をアップロード
        $imagePath = Cloudinary::upload($request->file('image_path')->getRealPath())->getSecurePath();

        // ユーザーの画像パスを更新
        $user->image_path = $imagePath;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'アイコンが更新されました。');
    } catch (\Exception $e) {
        // エラーメッセージをセッションに保存してリダイレクト
        return redirect()->route('profile.edit')->with('error', 'アイコンの更新中にエラーが発生しました。');
    }
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

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
