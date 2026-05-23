<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StepCompletion extends Model
{
    protected $fillable = [
        'guide_session_id',
        'step_id',
        'completed_at',
        'action_data',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'action_data' => 'array',
        ];
    }

    /**
     * @return BelongsTo<GuideSession, $this>
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(GuideSession::class, 'guide_session_id');
    }

    /**
     * @return BelongsTo<Step, $this>
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(Step::class);
    }
}
