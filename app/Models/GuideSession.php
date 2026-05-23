<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuideSession extends Model
{
    protected $fillable = [
        'guide_id',
        'reader_token',
        'reader_ip',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Guide, $this>
     */
    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    /**
     * @return HasMany<StepCompletion, $this>
     */
    public function completions(): HasMany
    {
        return $this->hasMany(StepCompletion::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }
}
