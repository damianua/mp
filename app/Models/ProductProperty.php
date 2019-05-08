<?php

namespace App\Models;

class ProductProperty extends BaseModel
{
    protected $fillable = [
        'active', 'sort', 'name', 'external_id'
    ];
    protected $casts = [
    	'active' => 'boolean'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->withPivot(['sort', 'require'])
            ->withTimestamps();
    }
}
