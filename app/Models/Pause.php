<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pause extends Model
{
    use HasFactory;

    // Add the fillable attributes for mass assignment.
    protected $fillable = ['shift_id', 'pause_on', 'pause_off'];

    public function shift(): BelongsTo
    { 
        return $this->belongsTo(Shift::class); 
    }
}
