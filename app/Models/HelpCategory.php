<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(HelpArticle::class, 'category_id', 'id');
    }
}
