<?php

namespace App\Models\Wordpress;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $connection = 'wordpress';
    protected $table = 'posts';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'post_title',
        'post_content',
        'post_status',
        'post_type',
        'guid',
        'post_parent',
    ];

    /**
     * Metadata relationship
     */
    public function metadata(): HasMany
    {
        return $this->hasMany(PostMeta::class, 'post_id', 'ID');
    }

    /**
     * Terms relationship (many-to-many via term_relationships)
     */
    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(
            Term::class,
            'term_relationships',
            'object_id',
            'term_taxonomy_id'
        )->withPivot('term_order');
    }

    /**
     * Get thumbnail if _thumbnail_id is present
     */
    public function thumbnail(): ?Post
    {
        $thumbnailId = $this->metadata->firstWhere('meta_key', '_thumbnail_id')?->meta_value;

        if ($thumbnailId) {
            return Post::where('ID', $thumbnailId)->where('post_type', 'attachment')->first();
        }

        return null;
    }

}
