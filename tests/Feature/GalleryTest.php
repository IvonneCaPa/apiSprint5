<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_list_galleries(){
        $this->withoutExceptionHandling();

        $response = $this->get(route('api.galleries.index'));
        $response->assertStatus(200);
        $this->assertArrayHasKey('galleries', $response->json());
    }
}
