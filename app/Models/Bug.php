<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $project_id
 * @property string $bug_key
 * @property string $title
 * @property string $description
 * @property string|null $steps_to_reproduce
 * @property string|null $expected_result
 * @property string|null $actual_result
 * @property string|null $environment
 * @property string $severity
 * @property string $priority
 * @property string $status
 * @property int $reported_by
 * @property int|null $assigned_to
 * @property \Illuminate\Support\Carbon|null $resolved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $reporter
 * @property-read \App\Models\User|null $assignee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BugComment> $comments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BugActivity> $activities
 */
class Bug extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'bug_key',
        'title',
        'description',
        'steps_to_reproduce',
        'expected_result',
        'actual_result',
        'environment',
        'severity',
        'priority',
        'status',
        'reported_by',
        'assigned_to',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return ['resolved_at' => 'datetime'];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BugComment::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(BugActivity::class);
    }
}
