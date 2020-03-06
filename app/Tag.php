<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $visible = [
        'id',
        'name'
    ];

    public function tag()
    {
        return $this->belongsToMany(Note::class);
    }
}
