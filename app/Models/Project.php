<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'title',
        'description',
        'client_id',
        'status',
        'deadline'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
