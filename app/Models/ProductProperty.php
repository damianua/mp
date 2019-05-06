<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model
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
