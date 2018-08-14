<?php

namespace Taxusorg\Permission\Repositories\Illuminate;

use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    protected $table = 'role_permits';

    protected $fillable = ['permit_key'];

    public function role()
    {
        return $this->belongsTo(Resource::class, 'role_id', 'id');
    }
}
