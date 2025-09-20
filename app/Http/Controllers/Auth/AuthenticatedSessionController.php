<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * 認証セッション管理コントローラー
 * 
 * ユーザーのログイン・ログアウト機能を提供します：
 * - ログインフォームの表示
 * - ログイン処理
 * - ログアウト処理
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * ログインフォーム表示
     * 
     * @return View ログインフォーム
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     * 
     * @param LoginRequest $request バリデーション済みのログインリクエスト
     * @return RedirectResponse ホームページへのリダイレクト
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 認証処理
        $request->authenticate();

        // セッションを再生成（セキュリティ向上）
        $request->session()->regenerate();

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * ログアウト処理
     * 
     * @param Request $request
     * @return RedirectResponse ホームページへのリダイレクト
     */
    public function destroy(Request $request): RedirectResponse
    {
        // ログアウト処理
        Auth::guard('web')->logout();

        // セッションを無効化
        $request->session()->invalidate();

        // CSRFトークンを再生成
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
