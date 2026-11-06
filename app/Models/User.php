<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_active' => 'boolean'];
    }

    public function reportedBugs(): HasMany { return $this->hasMany(Bug::class, 'reported_by'); }
    public function assignedBugs(): HasMany { return $this->hasMany(Bug::class, 'assigned_to'); }
    public function createdProjects(): HasMany { return $this->hasMany(Project::class, 'created_by'); }
    public function isAdmin(): bool { return $this->role === 'admin'; }
}
