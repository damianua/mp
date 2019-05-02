<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['id', 'active', 'sort', 'name'];
    protected $casts = [
        'active' => 'boolean'
    ];

    public function productProperties()
    {
        return $this->belongsToMany(ProductProperty::class)
            ->withPivot('sort', 'require')
            ->withTimestamps();
    }
}
