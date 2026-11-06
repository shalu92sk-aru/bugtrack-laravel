<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class BugComment extends Model {
    protected $fillable=['bug_id','user_id','comment'];
    public function bug(): BelongsTo { return $this->belongsTo(Bug::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
