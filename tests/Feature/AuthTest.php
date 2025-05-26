<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login(){

        $this->withoutExceptionHandling();
        
        #teniendo
        $user = User::create([
            'name'=>'Manolita',
            'email'=>'info@info.com',
            'password'=>bcrypt('123456')
        ]);

        // Crear el cliente personal de Passport
        $this->artisan('passport:client', [
            '--personal' => true,
            '--name' => 'Testing Client',
            '--provider' => 'users'
        ]);
        
        #haciendo
        $response = $this->post(route('api.login'),[
            'email'=>'info@info.com',
            'password'=>'123456'
        ]);

        #esperando
        $response->assertStatus(200);
        $this->assertArrayHasKey('access_token', $response->json());
    }

    public function test_register(){
        
        $this->artisan('passport:client', [
            '--personal' => true,
            '--name' => 'Testing Client',
            '--provider' => 'users'
        ]);
        
        $this->withoutExceptionHandling();

        $response = $this->post(route('api.register'), [
            'name'=> 'Manolita',
            'email' => 'info@info.com',
            'role' => User::ADMINISTRADOR,
            'password' => '123456'
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('access_token', $response->json());
    }
}
   