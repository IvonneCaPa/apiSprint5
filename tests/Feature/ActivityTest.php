<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Activity;

class ActivityTest extends TestCase
{
    use RefreshDatabase; 
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
}
