<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = ['name','key','description','created_by','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
    public function creator(): BelongsTo { return $this->belongsTo(User::class,'created_by'); }
    public function bugs(): HasMany { return $this->hasMany(Bug::class); }
}
