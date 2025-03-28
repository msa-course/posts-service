<?php

namespace App\Models;

use App\Models\Factories\PostFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $writer_id - id автора
 * @property string $title - заголовок
 * @property string $text - текст
 *
 * @property-read Writer|null $writer
 */
class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = ['writer_id', 'title', 'text'];

    public function writer(): BelongsTo
    {
        return $this->belongsTo(Writer::class, 'writer_id');
    }

    public static function factory(): PostFactory
    {
        return PostFactory::new();
    }
}
