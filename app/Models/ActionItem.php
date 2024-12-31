<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionItem extends Model
{
    use HasFactory;

    public function record_number()
    {
        return $this->morphOne(QmsRecordNumber::class, 'recordable');
    }


    public function getRecordNumberAttribute($value)
    {
        if ($this->relationLoaded('record_number')) {
            return optional($this->getRelationValue('record_number'))->record_number ?: $value;
        }

        $related = $this->record_number()->first();
        return $related;
    }
}
