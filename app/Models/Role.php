<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $table = 'roles';
    protected $fillable = ['name', 'guard_name', 'team_id'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'team_id', 'id');
    }
}
