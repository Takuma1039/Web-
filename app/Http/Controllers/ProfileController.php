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
    // 現在のURLとページ名を取得
    $currentUrl = url()->current();
    $currentPageName = 'プロフィール編集'; // 適切なページ名に変更

    // 履歴の管理
    $this->updateHistory($request, $currentUrl, $currentPageName);

    // 履歴を取得
    $history = $request->session()->get('history', []);
    
    // 最新のページがすでに履歴にある場合は更新
    if (count($history) > 0 && end($history)['url'] == $currentUrl) {
        return view('profile.edit', [
            'user' => $request->user(),
            'history' => $history,
        ]); // 履歴が同じ場合はビューを返すだけ
    }
    
    // プロフィール編集ページを表示
    return view('profile.edit', [
        'user' => $request->user(),
        'history' => $history, // 履歴をビューに渡す場合
    ]);
}

    // 履歴を更新するメソッド
private function updateHistory(Request $request, $currentUrl, $currentPageName)
{
    $history = $request->session()->get('history', []);
    
    // 古い履歴を削除するロジック
    if (count($history) >= 5) {
        array_shift($history); // 最初の履歴を削除
    }

    // 同じURLが連続して追加されないようにする
    if (count($history) > 0 && end($history)['url'] == $currentUrl) {
        $request->session()->put('history', $history); // 更新後にセッションに保存
        return;
    }

    // 新しい履歴を追加
    $history[] = [
        'url' => $currentUrl,
        'name' => $currentPageName
    ];

    $request->session()->put('history', $history); // 更新後にセッションに保存
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
        'image_path' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
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
