<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard user for login test
        $this->user = User::create([
            'name' => 'Petugas Perpus',
            'email' => 'petugas@iainsorong.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'active'
        ]);
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSeeLivewire(\App\Livewire\Admin\Login::class);
    }

    public function test_authenticated_user_cannot_view_login_page(): void
    {
        $response = $this->actingAs($this->user)->get('/admin/login');

        $response->assertRedirect('/admin');
    }

    public function test_login_requires_email_and_password(): void
    {
        Livewire::test(\App\Livewire\Admin\Login::class)
            ->call('login')
            ->assertHasErrors(['email', 'password']);
    }

    public function test_login_requires_valid_email_format(): void
    {
        Livewire::test(\App\Livewire\Admin\Login::class)
            ->set('email', 'not-an-email')
            ->set('password', 'password123')
            ->call('login')
            ->assertHasErrors(['email' => 'email']);
    }

    public function test_login_authenticates_and_redirects_user_with_correct_credentials(): void
    {
        Livewire::test(\App\Livewire\Admin\Login::class)
            ->set('email', 'petugas@iainsorong.ac.id')
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($this->user);
    }

    public function test_login_fails_with_incorrect_credentials(): void
    {
        Livewire::test(\App\Livewire\Admin\Login::class)
            ->set('email', 'petugas@iainsorong.ac.id')
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/admin/logout');

        $response->assertRedirect('/admin/login');
        $this->assertGuest();
    }
}
