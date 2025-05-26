<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Activity;
use App\Models\User;

class ActivityTest extends TestCase
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
 
    //ver todas
    public function test_activities_can_be_retrieved(){
        $this->withoutExceptionHandling();

        $response = $this->get(route('api.activities.index'));
        $response->assertStatus(200);
        $this->assertArrayHasKey('activities', $response->json());
    }

        // ver una
    public function test_activity_can_be_retrieved()
    {
        $this->withoutExceptionHandling();

        $activity = Activity::create([
            'title'=>'Exposición fotos',
           'description'=>'Exposición fotografica del taller Senegal',
           'site'=>'Centro Civico X',
           'datetime'=>'2025-08-15 17:00:11'
        ]);

        $response = $this->get(route('api.activity.show', $activity->id));

        $this->assertEquals($activity->title, 'Exposición fotos');
        $this->assertEquals($activity->description, 'Exposición fotografica del taller Senegal');
        $this->assertEquals($activity->site, 'Centro Civico X');
        $this->assertEquals($activity->datetime, '2025-08-15 17:00:11');

        $response->assertStatus(200);
    }

    //crear
    public function test_a_activity_can_be_created()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('api.activity.store'), [
            'title' => 'Exposición fotos',
            'description' => 'Exposición fotografica del taller Senegal',
            'site' => 'Centro Civico X',
            'datetime' => '2025-08-15 17:00:11'
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, Activity::all());
    }
}
