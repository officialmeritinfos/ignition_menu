<?php

namespace App\Models\Wordpress;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $connection = 'wordpress';
    protected $table = 'posts';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $casts = [
        'post_date' => 'datetime',
        'post_modified' => 'datetime',
    ];

    public function metadata()
    {
        return $this->hasMany(MenuMeta::class, 'post_id', 'ID');
    }

    public function terms()
    {
        return $this->belongsToMany(Term::class, 'term_relationships', 'object_id', 'term_taxonomy_id')
            ->withPivot('term_taxonomy_id');
    }

}
