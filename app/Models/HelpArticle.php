<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpArticle extends Model
{
    protected $fillable = [
        'title',
        'body',
        'author_id',
        'category_id'
    ];

    /**
     * Get the category this article belongs to.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(HelpCategory::class, 'id', 'category_id');
    }
}
