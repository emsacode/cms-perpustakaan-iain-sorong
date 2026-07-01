<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SdgsTag extends Model
{
    protected $table = 'sdgs_tags';
    protected $guarded = [];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_sdgs_tag', 'sdgs_tag_id', 'article_id');
    }
}
