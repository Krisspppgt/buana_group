<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->merge(['phone' => $this->normalizePhone($request->input('phone'))]);

        $data = $request->validate([
            'phone' => ['required', 'regex:/^08\d{8,12}$/'],
            'password' => ['required', 'string'],
        ], $this->messages());

        if (Auth::attempt($data, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()
            ->withErrors(['phone' => 'Nomor HP atau password salah.'])
            ->onlyInput('phone');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->merge(['phone' => $this->normalizePhone($request->input('phone'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'phone' => ['required', 'regex:/^08\d{8,12}$/', 'unique:users,phone'],
            'campus_id' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
        ], $this->messages());

        // role tidak diambil dari input, otomatis 1 (pelanggan) dari default database.
        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /** Ubah +62812..., 62812..., 812... menjadi 0812... */
    private function normalizePhone(?string $phone): string
    {
        $p = preg_replace('/\D+/', '', (string) $phone);

        if (str_starts_with($p, '62')) {
            $p = '0' . substr($p, 2);
        } elseif (str_starts_with($p, '8')) {
            $p = '0' . $p;
        }

        return $p;
    }

    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'phone.regex' => 'Format nomor HP tidak valid (contoh: 081234567890).',
            'phone.unique' => 'Nomor HP sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'name.max' => 'Nama maksimal 40 karakter.',
            'campus_id.max' => 'NIM/NIP maksimal 20 karakter.',
        ];
    }
}
