<?php

namespace App\Models\Wordpress;

use Illuminate\Database\Eloquent\Model;

class TermTaxonomy extends Model
{
    protected $connection = 'wordpress';
    protected $table = 'term_taxonomy';
    protected $primaryKey = 'term_taxonomy_id';
    public $timestamps = false;
}
