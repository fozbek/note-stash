<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Note extends Model
{
    protected $fillable = [
        'name',
        'content',
        'user_id',
    ];
    protected $hidden = [
        'user_id'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param $tags
     */
    public function syncTags($tags): void
    {
        $tags = collect($tags)
            ->map(function ($tag) {
                return Tag::firstOrCreate([
                    'name' => $tag
                ]);
            })->map(function ($tag) {
                return $tag->id;
            });

        $this->tags()->sync($tags);
    }
}
