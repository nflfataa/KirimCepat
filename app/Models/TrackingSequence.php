<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['date', 'last_number'])]
class TrackingSequence extends Model
{
    protected $primaryKey = 'date';

    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'last_number' => 'integer',
        ];
    }
}
