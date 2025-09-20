<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * プロフィール管理コントローラー
 * 
 * ユーザープロフィールの管理機能を提供します：
 * - プロフィール情報の表示・編集
 * - メールアドレス変更
 * - パスワード変更
 * - アカウント削除
 */
class ProfileController extends Controller
{
    /**
     * プロフィール編集フォーム表示
     * 
     * @param Request $request
     * @return View プロフィール編集フォーム
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * メールアドレス変更画面表示
     * 
     * @param Request $request
     * @return View メールアドレス変更フォーム
     */
    public function editEmail(Request $request): View
    {
        return view('profile.email');
    }

    /**
     * パスワード変更画面表示
     * 
     * @param Request $request
     * @return View パスワード変更フォーム
     */
    public function editPassword(Request $request): View
    {
        return view('profile.password');
    }

    /**
     * プロフィール情報の更新
     * 
     * @param ProfileUpdateRequest $request バリデーション済みのプロフィール更新リクエスト
     * @return RedirectResponse プロフィール編集ページへのリダイレクト
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // プロフィール情報を更新
        $request->user()->fill($request->validated());

        // メールアドレスが変更された場合は認証を無効化
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * ユーザーアカウントの削除
     * 
     * @param Request $request パスワード確認を含むリクエスト
     * @return RedirectResponse ホームページへのリダイレクト
     */
    public function destroy(Request $request): RedirectResponse
    {
        // パスワード確認のバリデーション
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // ログアウト処理
        Auth::logout();

        // ユーザーアカウントを削除
        $user->delete();

        // セッションを無効化
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
