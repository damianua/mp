<?php

namespace App\Models;

class Handbook extends BaseModel
{
    protected $fillable = ['name', 'external_id', self::UPDATED_AT];

    public function handbookItems()
    {
        return $this->hasMany(HandbookItem::class);
    }
}
