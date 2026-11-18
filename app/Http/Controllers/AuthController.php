<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $initialTab = $request->query('tab', 'login');

        return view('auth.login', ['initialTab' => $initialTab]);
    }

    public function login(Request $request): RedirectResponse
    {
        $loginInput = trim((string) ($request->input('account') ?? $request->input('phone') ?? $request->input('login_account') ?? $request->input('email') ?? ''));
        $campusId = trim((string) ($request->input('campus_id') ?? $request->input('nim_nip') ?? $request->input('login_nim_nip') ?? ''));
        $password = (string) $request->input('password');

        $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (empty($loginInput) && empty($campusId)) {
            return back()
                ->withErrors([
                    'phone' => 'Email, Nomor HP, atau NIM/NIP wajib diisi.',
                    'account' => 'Email, Nomor HP, atau NIM/NIP wajib diisi.',
                ])
                ->withInput();
        }

        $remember = $request->boolean('remember');

        // 1. Coba autentikasi menggunakan Email jika formatnya email
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $loginInput, 'password' => $password], $remember)) {
                $request->session()->regenerate();

                return redirect()->intended('/');
            }
        }

        // 2. Coba autentikasi menggunakan Nomor HP (dengan normalisasi 08...)
        $normalizedPhone = $this->normalizePhone($loginInput);
        if (! empty($normalizedPhone) && Auth::attempt(['phone' => $normalizedPhone, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        // 3. Coba autentikasi menggunakan NIM / NIP (campus_id)
        $targetCampusId = ! empty($campusId) ? $campusId : $loginInput;
        if (! empty($targetCampusId)) {
            $userByCampus = User::where('campus_id', $targetCampusId)->first();
            if ($userByCampus && Hash::check($password, $userByCampus->password)) {
                Auth::login($userByCampus, $remember);
                $request->session()->regenerate();

                return redirect()->intended('/');
            }
        }

        // 4. Fallback: jika nomor HP tersimpan tanpa format standar
        if (! empty($loginInput) && Auth::attempt(['phone' => $loginInput, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()
            ->withErrors([
                'phone' => 'Email, Nomor HP, NIM/NIP, atau kata sandi tidak cocok.',
                'account' => 'Email, Nomor HP, NIM/NIP, atau kata sandi tidak cocok.',
            ])
            ->withInput($request->except('password'));
    }

    public function showRegister(Request $request)
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $rawName = $request->input('name', $request->input('fullName', $request->input('full-name')));
        $rawEmail = $request->input('email', $request->input('email-address'));
        $rawPhone = $request->input('phone', $request->input('whatsapp', $request->input('phone-number')));
        $rawCampusId = $request->input('campus_id', $request->input('idNumber', $request->input('academic-id')));
        $rawPassword = $request->input('password');
        $rawPasswordConfirm = $request->input('password_confirmation', $request->input('passwordConfirm', $request->input('password-confirm')));

        $normalizedPhone = $this->normalizePhone($rawPhone);

        $request->merge([
            'name' => $rawName,
            'email' => $rawEmail,
            'phone' => $normalizedPhone,
            'campus_id' => $rawCampusId,
            'password' => $rawPassword,
            'password_confirmation' => $rawPasswordConfirm,
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:50', 'unique:users,email'],
            'phone' => ['required', 'regex:/^08\d{8,12}$/', 'unique:users,phone'],
            'campus_id' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
        ], $this->messages());

        $roleValue = match ($request->input('role_register')) {
            'unwahas', 'campus', 'civitas' => Role::Civitas,
            'instansi', 'b2b', 'mitra' => Role::Coordinator,
            default => Role::Customer,
        };

        if (! empty($data['campus_id']) && $roleValue === Role::Customer) {
            $roleValue = Role::Civitas;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'campus_id' => $data['campus_id'] ?? null,
            'password' => $data['password'],
            'role' => $roleValue,
        ]);

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
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.regex' => 'Format nomor HP/WhatsApp tidak valid (contoh: 081234567890).',
            'phone.unique' => 'Nomor HP/WhatsApp sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'name.max' => 'Nama maksimal 40 karakter.',
            'campus_id.max' => 'NIM/NIP maksimal 20 karakter.',
        ];
    }
}
