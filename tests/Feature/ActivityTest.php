<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
}
