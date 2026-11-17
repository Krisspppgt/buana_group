<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_home_dashboard(): void
    {
        $user = User::first() ?? User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Modul Unit Usaha Utama');
        $response->assertSee('Air Mineral Unwahas');
        $response->assertSee('Buana Food Services');
        $response->assertSee('Buana Tour & Travel', false);
        $response->assertSee('Buana Ticketing');
        $response->assertSee('Buana Konveksi');
    }
}
