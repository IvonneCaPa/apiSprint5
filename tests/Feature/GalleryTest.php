<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class GalleryTest extends TestCase
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
    /**
     * A basic feature test example.
     */
    public function test_galleries_can_be_retrieved()
    {
        $this->withoutExceptionHandling();

        $response = $this->get(route('api.galleries.index'));
        $response->assertStatus(200);
        $this->assertArrayHasKey('galleries', $response->json());
    }
}