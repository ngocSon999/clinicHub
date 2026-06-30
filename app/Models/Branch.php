<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $table = 'branches';

    protected $fillable = [
        'name',
        'code',
        'phone',
        'province_id',
        'commune_id',
        'address_detail',
        'full_address',
        'status'
    ];


    public function roles(): HasMany
    {
        return $this->hasMany(Role::class, 'team_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_roles',
            'team_id',
            'model_id'
        )->withPivot('role_id');
    }
}
