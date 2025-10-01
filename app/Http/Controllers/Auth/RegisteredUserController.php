<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * ユーザー登録コントローラー
 * 
 * 新規ユーザー登録機能を提供します：
 * - 登録フォームの表示
 * - 新規ユーザー登録処理
 * - 登録後の自動ログイン
 */
class RegisteredUserController extends Controller
{
    /**
     * 登録フォーム表示
     * 
     * @return View|RedirectResponse 登録フォームまたはマイページへのリダイレクト
     */
    public function create()
    {
        // ログイン済みユーザーはマイページにリダイレクト
        if (Auth::check()) {
            return redirect()->route('mypage')
                ->with('info', '既にログイン済みです。マイページに移動しました。');
        }

        return view('auth.register');
    }

    /**
     * 新規ユーザー登録処理
     * 
     * @param Request $request 登録データを含むリクエスト
     * @return RedirectResponse ホームページへのリダイレクト
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // バリデーション
        $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ユーザー作成
        $user = User::create([
            'name' => $request->display_name, // Breeze互換のためnameにも保存
            'display_name' => $request->display_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 登録イベントを発火（メール認証など）
        event(new Registered($user));

        // 自動ログイン
        Auth::login($user);

        return redirect()->route('home');
    }
}
