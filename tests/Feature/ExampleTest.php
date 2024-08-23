<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic test example.
     */
    // test_<WhatIsBeingTested>_<ExpectedBehavior>.
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->getJson('/api/posts');
        
        // $response->assertJson([]);
        // $response->assertExactJson([]);
        $response->assertJsonPath('data', []);
        // $response->assertJson(['data' => []]);

        $response->assertOk();
        // $response->assertSee('daniel zlotnik');
    }
}
