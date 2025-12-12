<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PostStatus;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string $body
 * @property-read PostStatus $status
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
        'status',
    ];

    protected $casts = [
        'status' => PostStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PostStatus::Published);
    }
}
