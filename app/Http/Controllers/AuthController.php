<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\VerificationStatus;
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
            'password' => $this->passwordRules(),
        ], [
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.max' => 'Kata sandi maksimal 72 karakter.',
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
        $rawCampusId = trim((string) $request->input('campus_id', $request->input('idNumber', $request->input('academic-id'))));
        $rawFaculty = trim((string) $request->input('faculty', ''));
        $rawCompanyName = trim((string) $request->input('company_name', ''));
        $rawPosition = trim((string) $request->input('position', ''));
        $rawPassword = $request->input('password');
        $rawPasswordConfirm = $request->input('password_confirmation', $request->input('passwordConfirm', $request->input('password-confirm')));

        $normalizedPhone = $this->normalizePhone($rawPhone);

        $requestedRole = match ($request->input('role_register')) {
            'unwahas', 'campus', 'civitas' => Role::Civitas,
            'instansi', 'b2b', 'mitra' => Role::Coordinator,
            default => null,
        };

        if ($requestedRole === null && $rawCampusId !== '') {
            $requestedRole = Role::Civitas;
        }

        $request->merge([
            'name' => $rawName,
            'email' => $rawEmail,
            'phone' => $normalizedPhone,
            'campus_id' => $rawCampusId !== '' ? $rawCampusId : null,
            'faculty' => $rawFaculty !== '' ? $rawFaculty : null,
            'company_name' => $rawCompanyName !== '' ? $rawCompanyName : null,
            'position' => $rawPosition !== '' ? $rawPosition : null,
            'password' => $rawPassword,
            'password_confirmation' => $rawPasswordConfirm,
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:50', 'unique:users,email'],
            'phone' => ['required', 'regex:/^08\d{8,12}$/', 'unique:users,phone'],
            'campus_id' => $requestedRole === Role::Civitas
                ? ['required', 'string', 'max:20']
                : ['nullable', 'string', 'max:20'],
            'faculty' => $requestedRole === Role::Civitas
                ? ['required', 'string', 'max:80']
                : ['nullable', 'string', 'max:80'],
            'company_name' => $requestedRole === Role::Coordinator
                ? ['required', 'string', 'max:100']
                : ['nullable', 'string', 'max:100'],
            'position' => $requestedRole === Role::Coordinator
                ? ['required', 'string', 'max:60']
                : ['nullable', 'string', 'max:60'],
            'password' => $this->passwordRules(confirmed: true),
        ], $this->messages());

        $isRequestingVerification = $requestedRole !== null;

        // Semua pendaftar langsung dapat login & bertransaksi reguler (Lunas/DP) sebagai Customer;
        // hak akses khusus Civitas/Mitra aktif setelah diverifikasi admin.
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'campus_id' => $requestedRole === Role::Civitas ? $data['campus_id'] : null,
            'faculty' => $requestedRole === Role::Civitas ? $data['faculty'] : null,
            'company_name' => $requestedRole === Role::Coordinator ? $data['company_name'] : null,
            'position' => $requestedRole === Role::Coordinator ? $data['position'] : null,
            'password' => $data['password'],
            'role' => Role::Customer,
            'requested_role' => $requestedRole,
            'verification_status' => $isRequestingVerification ? VerificationStatus::Pending : VerificationStatus::None,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($isRequestingVerification) {
            return redirect('/')->with(
                'info',
                "Akun berhasil dibuat. Anda sudah dapat memesan layanan reguler (Lunas/DP); pengajuan status {$requestedRole->label()} sedang menunggu verifikasi admin."
            );
        }

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
            $p = '0'.substr($p, 2);
        } elseif (str_starts_with($p, '8')) {
            $p = '0'.$p;
        }

        return $p;
    }

    /**
     * Aturan validasi kata sandi ketat: min 8 karakter, diawali huruf kapital, dan mengandung angka.
     *
     * @return array<int, mixed>
     */
    private function passwordRules(bool $confirmed = false): array
    {
        $rules = [
            'required',
            'string',
            'min:8',
            'max:72',
            function (string $attribute, mixed $value, \Closure $fail): void {
                $password = (string) $value;

                if (! preg_match('/^[A-Z]/', $password)) {
                    $fail('Huruf pertama kata sandi harus huruf kapital.');
                }

                if (! preg_match('/\d/', $password)) {
                    $fail('Kata sandi harus mengandung minimal satu angka.');
                }
            },
        ];

        if ($confirmed) {
            $rules[] = 'confirmed';
        }

        return $rules;
    }

    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'campus_id.required' => 'NIM / NIP wajib diisi untuk pengajuan akun Civitas.',
            'faculty.required' => 'Fakultas / Unit Kerja wajib dipilih untuk pengajuan akun Civitas.',
            'company_name.required' => 'Nama Instansi / Lembaga wajib diisi untuk pengajuan akun Mitra.',
            'position.required' => 'Jabatan PIC wajib diisi untuk pengajuan akun Mitra.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.regex' => 'Format nomor HP/WhatsApp tidak valid (contoh: 081234567890).',
            'phone.unique' => 'Nomor HP/WhatsApp sudah terdaftar.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.max' => 'Kata sandi maksimal 72 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'name.max' => 'Nama maksimal 40 karakter.',
            'campus_id.max' => 'NIM/NIP maksimal 20 karakter.',
        ];
    }
}
