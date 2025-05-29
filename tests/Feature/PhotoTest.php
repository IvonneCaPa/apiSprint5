<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PhotoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    //todos
    public function test_photos_can_be_retrieved()
    {
        $this->withoutExceptionHandling();

        $response = $this->get(route('api.photos.index'));
        $response->assertStatus(200);
        $this->assertArrayHasKey('photos', $response->json());
    }
        
}
