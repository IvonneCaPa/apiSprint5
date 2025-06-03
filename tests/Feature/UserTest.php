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
            'role'=>User::ADMINISTRADOR,
            'password'=>bcrypt('123456')
        ]);

        $response = $this->post(route('api.login'), [
            'email' => $user->email,
            'password' => '123456'
        ]);

        return $response->json()['access_token'];
    }

    // ver todos
    public function test_a_users_can_be_retrieved()
    {
        $this->withoutExceptionHandling();

        $response = $this->get(route('api.users.index'));
        $response->assertStatus(200);
        $this->assertArrayHasKey('users', $response->json());
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

    // test para editar un usuario
    public function test_a_user_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $token = $this->authenticated();

        $user = User::create([
            'name' => 'Usuario Original',
            'email' => 'original@test.com',
            'role' => User::USUARIO,
            'password' => bcrypt('password123')
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->put(route('api.user.update', $user->id), [
            'name' => 'Usuario Actualizado',
            'email' => 'actualizado@test.com',
            'role' => User::ADMINISTRADOR
        ]);

        $updatedUser = User::find($user->id);

        $this->assertEquals($updatedUser->name, 'Usuario Actualizado');
        $this->assertEquals($updatedUser->email, 'actualizado@test.com');
        $this->assertEquals($updatedUser->role, User::ADMINISTRADOR);
        
        $this->assertArrayHasKey('user', $response->json());
        $response->assertJsonMissing(['error']);
        $response->assertStatus(200);
    }

}
