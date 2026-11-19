<?php

namespace Tests\Feature;

use App\Mail\PasswordOtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_is_accessible_for_guests(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('Lupa Kata Sandi?');
    }

    public function test_user_can_reset_password_using_otp_sent_to_email(): void
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'budi@gmail.com']);

        $this->post(route('password.otp.send'), [
            'account' => 'budi@gmail.com',
            'channel' => 'email',
        ])->assertRedirect(route('password.verify'));

        $sentOtp = null;
        Mail::assertSent(PasswordOtpMail::class, function (PasswordOtpMail $mail) use ($user, &$sentOtp): bool {
            $sentOtp = $mail->otp;

            return $mail->hasTo($user->email);
        });

        $this->get(route('password.verify'))
            ->assertOk()
            ->assertSee('bu***@gmail.com');

        $this->post(route('password.reset'), [
            'otp' => $sentOtp,
            'password' => 'Rahasia123',
            'password_confirmation' => 'Rahasia123',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('Rahasia123', $user->fresh()->password));
    }

    public function test_wrong_otp_does_not_change_password(): void
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'budi@gmail.com']);

        $this->post(route('password.otp.send'), [
            'account' => 'budi@gmail.com',
            'channel' => 'email',
        ]);

        $sentOtp = null;
        Mail::assertSent(PasswordOtpMail::class, function (PasswordOtpMail $mail) use (&$sentOtp): bool {
            $sentOtp = $mail->otp;

            return true;
        });

        $wrongOtp = $sentOtp === '000000' ? '111111' : '000000';

        $this->from(route('password.verify'))
            ->post(route('password.reset'), [
                'otp' => $wrongOtp,
                'password' => 'Rahasia123',
                'password_confirmation' => 'Rahasia123',
            ])
            ->assertRedirect(route('password.verify'))
            ->assertSessionHasErrors('otp');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_unknown_account_returns_error_without_sending_email(): void
    {
        Mail::fake();

        $this->from(route('password.request'))
            ->post(route('password.otp.send'), [
                'account' => 'tidakada@gmail.com',
                'channel' => 'email',
            ])
            ->assertRedirect(route('password.request'))
            ->assertSessionHasErrors('account');

        Mail::assertNothingSent();
    }

    public function test_user_can_reset_password_using_otp_sent_to_whatsapp_via_fonnte(): void
    {
        config(['services.fonnte.token' => 'test-fonnte-token']);
        Http::fake([
            'api.fonnte.com/send' => Http::response(['status' => true, 'detail' => 'success! message in queue']),
        ]);

        $user = User::factory()->create(['phone' => '081234567890']);

        $this->post(route('password.otp.send'), [
            'account' => '+62 812-3456-7890',
            'channel' => 'whatsapp',
        ])->assertRedirect(route('password.verify'));

        $sentOtp = null;
        Http::assertSent(function (Request $request) use (&$sentOtp): bool {
            preg_match('/\*(\d{6})\*/', (string) $request['message'], $matches);
            $sentOtp = $matches[1] ?? null;

            return $request->url() === 'https://api.fonnte.com/send'
                && $request->hasHeader('Authorization', 'test-fonnte-token')
                && $request->isForm()
                && $request['target'] === '081234567890'
                && $request['connectOnly'] === 'true';
        });

        $this->assertNotNull($sentOtp);

        $this->get(route('password.verify'))
            ->assertOk()
            ->assertSee('WhatsApp')
            ->assertSee('0812*****890');

        $this->post(route('password.reset'), [
            'otp' => $sentOtp,
            'password' => 'Rahasia123',
            'password_confirmation' => 'Rahasia123',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('Rahasia123', $user->fresh()->password));
    }

    public function test_whatsapp_otp_fails_gracefully_when_fonnte_device_is_disconnected(): void
    {
        config(['services.fonnte.token' => 'test-fonnte-token']);
        Http::fake([
            'api.fonnte.com/send' => Http::response(['status' => false, 'reason' => 'device disconnected']),
        ]);

        User::factory()->create(['phone' => '081234567890']);

        $this->from(route('password.request'))
            ->post(route('password.otp.send'), [
                'account' => '081234567890',
                'channel' => 'whatsapp',
            ])
            ->assertRedirect(route('password.request'))
            ->assertSessionHasErrors('channel');

        $this->get(route('password.verify'))->assertRedirect(route('password.request'));
    }
}
