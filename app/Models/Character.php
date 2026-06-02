<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'character',
        'pinyin',
        'definition',
        'radical',
        'decomposition',
        'etymology',
        'stroke_count',
    ];

    protected $casts = [
        'pinyin' => 'array',
    ];

    // Аксессор для получения pinyin как строки
    public function getPinyinStringAttribute(): string
    {
        if (is_array($this->pinyin)) {
            return implode(', ', $this->pinyin);
        }
        return $this->pinyin ?? '';
    }

    // Связь с тегами (многие-ко-многим)
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // Связь с избранным
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Проверка, добавлен ли в избранное
    public function isFavorite($sessionId = null)
    {
        $sessionId = $sessionId ?? session()->getId();
        return $this->favorites()->where('session_id', $sessionId)->exists();
    }

    // Аксессор для получения списка тегов
    public function getTagsListAttribute()
    {
        return $this->tags->pluck('name')->implode(', ');
    }

    // Скоуп для поиска
    public function scopeSearch($query, $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where('character', 'like', "%{$term}%")
            ->orWhere('pinyin', 'like', "%{$term}%")
            ->orWhere('definition', 'like', "%{$term}%");
    }

    // Скоуп для фильтрации по радикалу
    public function scopeByRadical($query, $radical)
    {
        if (empty($radical)) {
            return $query;
        }
        return $query->where('radical', $radical);
    }
}
