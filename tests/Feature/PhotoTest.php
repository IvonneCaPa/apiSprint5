<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Gallery;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class PhotoTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    //todos
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

    public function test_photos_can_be_retrieved()
    {
        $this->withoutExceptionHandling();

        $response = $this->get(route('api.photos.index'));
        $response->assertStatus(200);
        $this->assertArrayHasKey('photos', $response->json());
    }

    public function test_a_photo_can_be_uploaded()
    {
        $this->withoutExceptionHandling();
        
        Storage::fake('public');

        $token = $this->authenticated();

        $gallery = Gallery::create([
            'title' => 'Exposición fotos',
            'date' => '2025-08-15',
            'site' => 'Centro Civico X'
        ]);

        $file = UploadedFile::fake()->image('foto_test.jpg');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->post(route('api.photo.store'), [
            'gallery_id' => $gallery->id,
            'title' => 'Foto de prueba',
            'location' => $file 
        ]);

        $response->assertStatus(200);
        
        $this->assertCount(1, Photo::all());
        $this->assertArrayHasKey('photo', $response->json());
        
        $photo = Photo::first();
        Storage::disk('public')->assertExists($photo->location);
        
        $this->assertEquals($gallery->id, $photo->gallery_id);
        $this->assertEquals('Foto de prueba', $photo->title);
    }
}
