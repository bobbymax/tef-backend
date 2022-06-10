<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function modules()
    {
        return $this->morphedByMany(Module::class, 'roleable');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'roleable');
    }

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'permissionable');
    }

    public function grantPermission(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }
}
