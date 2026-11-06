<?php

namespace Tests\Feature;

use App\Models\Bug;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_cannot_access_team_management(): void
    {
        $developer = User::factory()->create(['role' => 'developer']);

        $response = $this->actingAs($developer)->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Jane Developer',
            'email' => 'jane@example.com',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
            'role' => 'developer',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'name' => 'Jane Developer',
            'role' => 'developer',
            'is_active' => true,
        ]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));

        $response->assertStatus(422);
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_cannot_delete_user_with_associated_bugs(): void
    {
        $admin = User::factory()->admin()->create();
        $developer = User::factory()->create(['role' => 'developer']);
        $project = Project::factory()->create();

        Bug::factory()->create([
            'project_id' => $project->id,
            'reported_by' => $developer->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('users.destroy', $developer));

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('users', ['id' => $developer->id]);
    }
}
