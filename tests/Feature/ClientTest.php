<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;

class ClientTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();

        // Authenticate as the user
        Sanctum::actingAs($this->user, ['*']);
    }

    /** @test */
    public function testUserCanCreateClient()
    {
        $response = $this->postJson('/api/clients', [
            'name' => 'Test Client',
            'email' => 'client@example.com',
            'contact_person' => 'John Doe',
        ]);

        $response->assertStatus(201);
    }
    // --------------------------------------------------------------
    // test will run same database and i checked only one endpoint ! other test case can be write from next here!
    // --------------------------------------------------------------
}
