<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $bug_id
 * @property int|null $user_id
 * @property string $action
 * @property string|null $field
 * @property string|null $old_value
 * @property string|null $new_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bug $bug
 * @property-read \App\Models\User|null $user
 */
class BugActivity extends Model
{
    protected $fillable = ['bug_id', 'user_id', 'action', 'field', 'old_value', 'new_value'];

    public function bug(): BelongsTo
    {
        return $this->belongsTo(Bug::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
