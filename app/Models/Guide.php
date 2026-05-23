<?php

namespace App\Models;

use App\Enums\GuideStatus;
use Database\Factories\GuideFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Guide extends Model
{
    /** @use HasFactory<GuideFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'edit_token',
        'creator_email',
        'creator_cookie',
        'creator_ip',
        'cover_image_path',
        'status',
        'published_at',
        'require_completion',
    ];

    protected function casts(): array
    {
        return [
            'status' => GuideStatus::class,
            'published_at' => 'datetime',
            'require_completion' => 'boolean',
        ];
    }

    /**
     * @return HasMany<Step, $this>
     */
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class)->orderBy('order');
    }

    /**
     * @return HasMany<GuideSession, $this>
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(GuideSession::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', GuideStatus::Published);
    }

    public function isPublished(): bool
    {
        return $this->status === GuideStatus::Published;
    }

    public function getCoverImageUrl(): ?string
    {
        if (! $this->cover_image_path) {
            return null;
        }

        if (str_starts_with($this->cover_image_path, 'images/')) {
            return asset($this->cover_image_path);
        }

        return asset('storage/'.$this->cover_image_path);
    }

    public static function generateUniqueSlug(): string
    {
        do {
            $slug = strtolower(Str::random(8));
        } while (self::where('slug', $slug)->exists());

        return $slug;
    }

    public static function generateEditToken(): string
    {
        do {
            $token = Str::random(32);
        } while (self::where('edit_token', $token)->exists());

        return $token;
    }
}
