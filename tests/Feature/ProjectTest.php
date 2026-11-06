<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_cannot_create_project(): void
    {
        $developer = User::factory()->create(['role' => 'developer']);

        $response = $this->actingAs($developer)->post(route('projects.store'), [
            'name' => 'Secret Project',
            'key' => 'SEC',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_project(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('projects.store'), [
            'name' => 'Customer Portal',
            'key' => 'PORTAL',
            'description' => 'A portal for customer tickets',
        ]);

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'key' => 'PORTAL',
            'name' => 'Customer Portal',
            'created_by' => $admin->id,
        ]);
    }

    public function test_admin_can_update_project(): void
    {
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($admin)->put(route('projects.update', $project), [
            'name' => 'Updated Project Name',
            'key' => $project->key,
            'is_active' => '0',
        ]);

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Project Name',
            'is_active' => false,
        ]);
    }
}
