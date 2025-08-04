<?php

namespace App\Domain\Scheduling\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $fillable = ['professional_id', 'name', 'description', 'duration', 'price'];

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }
}
