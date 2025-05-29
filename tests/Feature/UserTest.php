<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:client', [
            '--personal' => true,
            '--name' => 'Testing Client',
            '--provider' => 'users'
        ]);
    }

    public function authenticated(){
        $user = User::create([
            'name'=>'garfield',
            'email'=>rand(12345, 678910).'@info.com',
            'role'=>User::USUARIO,
            'password'=>bcrypt('123456')
        ]);

        $response = $this->post(route('api.login'), [
            'email' => $user->email,
            'password' => '123456'
        ]);

        return $response->json()['access_token'];
    }

    // test para recuperar un usuario
    public function test_a_user_can_be_retrieved()
    {        
        $this->withExceptionHandling();
        $token = $this->authenticated();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json'
        ])->get('/api/user');

        $response->assertStatus(200);
        $this->assertArrayHasKey('id', $response->json());
        $this->assertArrayHasKey('name', $response->json());
        $this->assertArrayHasKey('email', $response->json());
    }
}
