<?php

namespace App\Models\Wordpress;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $connection = 'wordpress';
    protected $table = 'terms';
    protected $primaryKey = 'term_id';
    public $timestamps = false;

    public function scopeCategories($query)
    {
        return $query->join('term_taxonomy', 'terms.term_id', '=', 'term_taxonomy.term_id')
            ->where('term_taxonomy.taxonomy', 'mp_menu_category');
    }

}
