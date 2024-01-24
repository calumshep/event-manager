<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HelpCategory extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'description'
    ];

    /**
     * Get the help articles in this category.
     *
     * @return HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(HelpArticle::class, 'category_id', 'id');
    }
}
