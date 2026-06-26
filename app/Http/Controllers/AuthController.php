<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SendVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'As credenciais informadas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $this->sendCode($user);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verify.form', ['email' => $user->email]);
    }

    public function showVerifyForm(Request $request): View|RedirectResponse
    {
        $email = $request->query('email', $request->user()?->email);

        if (!$email) {
            return redirect()->route('login');
        }

        return view('auth.verify-code', compact('email'));
    }

    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'array', 'size:4'],
            'code.*' => ['required', 'string', 'size:1', 'digits:1'],
        ]);

        $code = implode('', $request->code);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        if ($user->verification_code !== $code) {
            return back()->withErrors(['code' => 'Código inválido.'])->onlyInput('email');
        }

        if (!$user->verification_code_expires_at || $user->verification_code_expires_at->isPast()) {
            return back()->withErrors(['code' => 'Código expirado. Solicite um novo.'])->onlyInput('email');
        }

        $user->update([
            'email_verified_at' => now(),
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        return redirect()->route('dashboard');
    }

    public function resendCode(Request $request): RedirectResponse
    {
        $email = $request->query('email', $request->user()?->email);

        if (!$email) {
            return redirect()->route('login');
        }

        $user = User::where('email', $email)->first();

        if (!$user || $user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        $this->sendCode($user);

        return back()->with('resent', true);
    }

    protected function sendCode(User $user): void
    {
        $code = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $user->update([
            'verification_code' => $code,
            'verification_code_expires_at' => now()->addMinutes(10),
        ]);

        $user->notify(new SendVerificationCode($code));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
