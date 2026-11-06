<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bug extends Model
{
    protected $fillable = ['project_id','bug_key','title','description','steps_to_reproduce','expected_result','actual_result','environment','severity','priority','status','reported_by','assigned_to','resolved_at'];
    protected function casts(): array { return ['resolved_at'=>'datetime']; }
    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
    public function reporter(): BelongsTo { return $this->belongsTo(User::class,'reported_by'); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class,'assigned_to'); }
    public function comments(): HasMany { return $this->hasMany(BugComment::class); }
    public function activities(): HasMany { return $this->hasMany(BugActivity::class); }
}
