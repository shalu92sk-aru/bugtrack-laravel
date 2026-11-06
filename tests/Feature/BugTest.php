<?php

namespace Tests\Feature;

use App\Models\Bug;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BugTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/bugs');
        $response->assertRedirect('/login');
    }

    public function test_developer_can_see_assigned_or_reported_bugs(): void
    {
        $developer = User::factory()->create(['role' => 'developer']);
        $otherDev = User::factory()->create(['role' => 'developer']);
        $project = Project::factory()->create();

        $myBug = Bug::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $developer->id,
            'title' => 'My Assigned Bug',
        ]);

        $otherBug = Bug::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $otherDev->id,
            'title' => 'Other Dev Bug',
        ]);

        $response = $this->actingAs($developer)->get('/bugs');
        $response->assertStatus(200);
        $response->assertSee('My Assigned Bug');
        $response->assertDontSee('Other Dev Bug');
    }

    public function test_developer_cannot_view_unassigned_bug(): void
    {
        $developer = User::factory()->create(['role' => 'developer']);
        $otherDev = User::factory()->create(['role' => 'developer']);
        $project = Project::factory()->create();

        $unassignedBug = Bug::factory()->create([
            'project_id' => $project->id,
            'reported_by' => $otherDev->id,
            'assigned_to' => $otherDev->id,
        ]);

        $response = $this->actingAs($developer)->get(route('bugs.show', $unassignedBug));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_any_bug(): void
    {
        $admin = User::factory()->admin()->create();
        $otherDev = User::factory()->create(['role' => 'developer']);
        $project = Project::factory()->create();

        $bug = Bug::factory()->create([
            'project_id' => $project->id,
            'reported_by' => $otherDev->id,
            'assigned_to' => $otherDev->id,
        ]);

        $response = $this->actingAs($admin)->get(route('bugs.show', $bug));
        $response->assertStatus(200);
    }

    public function test_user_can_report_bug(): void
    {
        $user = User::factory()->create(['role' => 'developer']);
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->post(route('bugs.store'), [
            'project_id' => $project->id,
            'title' => 'Sample test issue',
            'description' => 'Detailed reproduction steps here.',
            'severity' => 'high',
            'priority' => 'urgent',
        ]);

        $this->assertDatabaseHas('bugs', [
            'project_id' => $project->id,
            'title' => 'Sample test issue',
            'severity' => 'high',
            'priority' => 'urgent',
            'reported_by' => $user->id,
        ]);

        $response->assertSessionHas('success');
    }

    public function test_only_admin_can_delete_bug(): void
    {
        $developer = User::factory()->create(['role' => 'developer']);
        $admin = User::factory()->admin()->create();
        $project = Project::factory()->create();

        $bug = Bug::factory()->create([
            'project_id' => $project->id,
            'reported_by' => $developer->id,
            'assigned_to' => $developer->id,
        ]);

        // Developer attempt -> 403
        $response = $this->actingAs($developer)->delete(route('bugs.destroy', $bug));
        $response->assertStatus(403);
        $this->assertDatabaseHas('bugs', ['id' => $bug->id]);

        // Admin attempt -> success
        $response = $this->actingAs($admin)->delete(route('bugs.destroy', $bug));
        $response->assertRedirect(route('bugs.index'));
        $this->assertDatabaseMissing('bugs', ['id' => $bug->id]);
    }
}
