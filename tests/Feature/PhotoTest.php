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
    //ver todos
    public function test_photos_can_be_retrieved()
    {
        $this->withoutExceptionHandling();

        $response = $this->get(route('api.photos.index'));
        $response->assertStatus(200);
        $this->assertArrayHasKey('photos', $response->json());
    }
    //crear
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
        $this->assertTrue(
     Storage::disk('public')->exists($photo->location),
            'El archivo de la foto no existe en el disco público'
        );
        
        $this->assertEquals($gallery->id, $photo->gallery_id);
        $this->assertEquals('Foto de prueba', $photo->title);
    }
    //ver 1
    public function test_a_photo_can_be_retrieved()
    {
        $this->withoutExceptionHandling();

        $gallery = Gallery::create([
            'title' => 'Exposición fotos',
            'date' => '2025-08-15',
            'site' => 'Centro Civico X'
        ]);

        $photo = Photo::create([
            'gallery_id' => $gallery->id,
            'title' => 'Foto de prueba',
            'location' => 'photos/test-image.jpg'
        ]);

        $response = $this->get(route('api.photo.show', $photo->id));

        $response -> assertStatus(200);
        $this->assertArrayHasKey('photo', $response->json());
    }

    //editar
    public function test_a_photo_can_be_update()
    {
        $this->withoutExceptionHandling();

        $token = $this->authenticated();

        $gallery = Gallery::create([
            'title' => 'Exposición fotos',
            'date' => '2025-08-15',
            'site' => 'Centro Civico X'
        ]);

        $file = UploadedFile::fake()->image('foto_test.jpg');
        
        $photo = Photo::create([
            'gallery_id' => $gallery->id,
            'title' => 'Foto de prueba',
            'location' => $file->hashName()
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' .$token,
            'Accept' => 'application/json'
        ])->put(route('api.photo.update', $photo->id),[
            'gallery_id' => $gallery->id,
            'title' => 'Foto 2',
            'location' => $file
        ]);

        $photoUpdated = Photo::find($photo->id);

        $this->assertEquals($photoUpdated->gallery_id, $gallery->id);
        $this->assertEquals($photoUpdated->title, 'Foto 2');
        $this->assertEquals($photoUpdated->location, 'photos/' . $file->hashName());

        $response->assertStatus(200);
        $response->assertJsonMissing(['error']);
    }
}
