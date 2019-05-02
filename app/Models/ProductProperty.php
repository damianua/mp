<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model
{
    protected $fillable = [
        'active', 'sort', 'name', 'external_id'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->withPivot(['sort', 'require'])
            ->withTimestamps();
    }
}
