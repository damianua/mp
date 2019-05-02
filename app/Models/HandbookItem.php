<?php

namespace App\Models;

class HandbookItem extends BaseModel
{
    protected $fillable = ['name', 'active', 'external_id', 'handbook_id', self::UPDATED_AT];
    protected $casts = [
        'active' => 'bool'
    ];

    public function handbook()
    {
        return $this->belongsTo(Handbook::class);
    }
}
