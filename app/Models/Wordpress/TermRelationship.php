<?php

namespace App\Models\Wordpress;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TermRelationship extends Pivot
{
    protected $connection = 'wordpress';
    protected $table = 'term_relationships';
    public $timestamps = false;

    public function taxonomy()
    {
        return $this->belongsTo(TermTaxonomy::class, 'term_taxonomy_id', 'term_taxonomy_id');
    }
}
