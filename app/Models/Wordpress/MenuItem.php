<?php

namespace App\Models\Wordpress;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * Get all metadata for the post.
     */
    public function metadata(): HasMany
    {
        return $this->hasMany(MenuMeta::class, 'post_id', 'ID');
    }
    /**
     * Get the menu item's categories (terms).
     */
    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'term_relationships', 'object_id', 'term_taxonomy_id')
            ->withPivot('term_taxonomy_id');
    }

    /**
     * Get only the _thumbnail_id metadata row.
     */
    public function thumbnailMeta(): HasOne
    {
        return $this->hasOne(MenuMeta::class, 'post_id', 'ID')
            ->where('meta_key', '_thumbnail_id');
    }


    public function thumbnailPost(): HasOne
    {
        return $this->hasOne(Post::class, 'ID', 'meta_value')
            ->join('postmeta', 'postmeta.meta_value', '=', 'posts.ID')
            ->where('postmeta.meta_key', '_thumbnail_id')
            ->where('posts.post_type', 'attachment')
            ->select('posts.*');
    }

    /**
     * Accessor for the menu item's thumbnail URL.
     */
    public function thumbnailUrl(): Attribute
    {
        return Attribute::get(function () {
            $meta = $this->thumbnailMeta ?? $this->metadata->firstWhere('meta_key', '_thumbnail_id');
            if (! $meta) {
                return null;
            }

            return Post::where('ID', $meta->meta_value)
                ->where('post_type', 'attachment')
                ->value('guid');
        });
    }
}
