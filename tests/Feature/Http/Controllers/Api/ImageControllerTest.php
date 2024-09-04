<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_image_is_stored_successfully(): void
    {
        Storage::fake('public');
        $this->actingAs($this->user);
        $image = UploadedFile::fake()->image('test-image.jpg');

        $response = $this->postJson('/api/v1/images', [
            'image' => $image
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'path',
                    'size',
                    'url',
                ]
            ]);
        Storage::disk('public')->assertExists($response->json('data.path'));
    }

    public function test_image_store_throws_validation_error(): void
    {
        Storage::fake('public');
        $this->actingAs($this->user);
        $image = UploadedFile::fake()->image('test-pdf.pdf');

        $response = $this->postJson('/api/v1/images', [
            'image' => $image
        ]);

        $response->assertStatus(422);
    }

    public function test_unauthenticated_image_store_throws_error(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('test-image.jpg');

        $response = $this->postJson('/api/v1/images', [
            'image' => $image
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
