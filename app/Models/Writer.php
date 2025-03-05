<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $name - имя
 * @property string $email
 * @property bool $active - активность
 *
 * @property-read Collection<int,Post> $posts
 *
 * @method static Builder|static whereActive()
 */
class Writer extends Model
{
    protected $table = 'writers';

    protected $casts = [
        'active' => 'bool',
    ];

    protected $fillable = ['name', 'email', 'active'];

    protected $attributes = [
        'active' => true,
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->orderByDesc('created_at');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }
}
