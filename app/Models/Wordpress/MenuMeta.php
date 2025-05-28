<?php

namespace App\Models\Wordpress;

use Illuminate\Database\Eloquent\Model;

class MenuMeta extends Model
{
    protected $connection = 'wordpress';
    protected $table = 'postmeta';
    protected $primaryKey = 'meta_id';
    public $timestamps = false;

    protected $fillable = ['post_id', 'meta_key', 'meta_value'];
}
