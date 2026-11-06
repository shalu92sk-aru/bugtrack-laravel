<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bug> $reportedBugs
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bug> $assignedBugs
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $createdProjects
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BugComment> $comments
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function reportedBugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'reported_by');
    }

    public function assignedBugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'assigned_to');
    }

    public function createdProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BugComment::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
