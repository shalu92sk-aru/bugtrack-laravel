<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class BugActivity extends Model {
    protected $fillable=['bug_id','user_id','action','field','old_value','new_value'];
    public function bug(): BelongsTo { return $this->belongsTo(Bug::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
