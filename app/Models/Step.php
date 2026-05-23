<?php

namespace App\Models;

use App\Enums\ActionType;
use App\Enums\ConditionType;
use App\Enums\MediaType;
use Database\Factories\StepFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Step extends Model
{
    /** @use HasFactory<StepFactory> */
    use HasFactory;

    protected $fillable = [
        'guide_id',
        'title',
        'content_html',
        'order',
        'media_path',
        'media_type',
        'action_type',
        'action_label',
        'condition_type',
        'condition_start',
        'condition_end',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'action_type' => ActionType::class,
            'media_type' => MediaType::class,
            'condition_type' => ConditionType::class,
        ];
    }

    /**
     * @return BelongsTo<Guide, $this>
     */
    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    public function getActionLabelText(): string
    {
        return $this->action_label ?? $this->action_type->label();
    }

    public function hasMedia(): bool
    {
        return $this->media_path !== null;
    }

    public function getMediaUrl(): ?string
    {
        if (! $this->media_path) {
            return null;
        }

        if (str_starts_with($this->media_path, 'images/')) {
            return asset($this->media_path);
        }

        return asset('storage/'.$this->media_path);
    }
}
