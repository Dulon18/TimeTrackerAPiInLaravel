<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'name',
        'email',
        'contact_person'
        ];

    public function project()
    {
        return $this->hasMany(Project::class);
    }
}
