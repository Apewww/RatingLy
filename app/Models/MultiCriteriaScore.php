<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MultiCriteriaScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'taste_score',
        'ambience_score',
        'wifi_socket_score',
        'price_value_score',
    ];

    protected $casts = [
        'taste_score' => 'decimal:1',
        'ambience_score' => 'decimal:1',
        'wifi_socket_score' => 'decimal:1',
        'price_value_score' => 'decimal:1',
    ];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }
}
