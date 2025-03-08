<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory;

    // Add the fillable attributes for mass assignment.
    protected $fillable = ['user_id', 'the_date', 'time_in', 'time_out'];

    public function user(): BelongsTo
    { 
        return $this->belongsTo(User::class); 
    }

    public function pauses(): HasMany 
    { 
        return $this->hasMany(Pause::class); 
    }

    public function snoozes(): HasMany 
    { 
        return $this->hasMany(Snooze::class); 
    }
}
