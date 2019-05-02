<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'parent_id', 'active', 'name', 'sort', 'left_margin', 'right_margin', 'external_id'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function parent()
    {
        return $this->belongsTo(Section::class, 'parent_id');
    }
}
