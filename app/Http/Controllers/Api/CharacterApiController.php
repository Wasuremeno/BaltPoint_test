<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\Tag;
use App\Models\Favorite;
use Illuminate\Http\Request;

class CharacterApiController extends Controller
{
    // Добавить/удалить из избранного
    public function toggleFavorite(Request $request, Character $character)
    {
        $sessionId = session()->getId();
        $favorite = $character->favorites()->where('session_id', $sessionId)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed', 'favorited' => false]);
        } else {
            Favorite::create([
                'character_id' => $character->id,
                'session_id' => $sessionId,
                'ip_address' => $request->ip(),
            ]);
            return response()->json(['status' => 'added', 'favorited' => true]);
        }
    }

    // Получить список избранного
    public function getFavorites()
    {
        $sessionId = session()->getId();
        $favorites = Favorite::with('character')
            ->where('session_id', $sessionId)
            ->get()
            ->pluck('character');

        return response()->json($favorites);
    }

    // Добавить тег к персонажу
    public function addTag(Request $request, Character $character)
    {
        $request->validate([
            'tag_name' => 'required|string|max:50'
        ]);

        $tag = Tag::firstOrCreate(
            ['name' => $request->tag_name],
            ['color' => $request->tag_color ?? '#6c757d']
        );

        $character->tags()->syncWithoutDetaching([$tag->id]);

        return response()->json([
            'status' => 'added',
            'tag' => $tag,
            'tags' => $character->tags
        ]);
    }

    // Удалить тег
    public function removeTag(Character $character, Tag $tag)
    {
        $character->tags()->detach($tag->id);

        return response()->json([
            'status' => 'removed',
            'tags' => $character->tags
        ]);
    }

    // Получить статистику
    public function getStats()
    {
        $total = Character::count();
        $withRadical = Character::whereNotNull('radical')->count();
        $withDefinition = Character::whereNotNull('definition')->where('definition', '!=', '')->count();

        $radicals = Character::selectRaw('radical, count(*) as count')
            ->whereNotNull('radical')
            ->groupBy('radical')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        return response()->json([
            'total' => $total,
            'with_radical' => $withRadical,
            'with_definition' => $withDefinition,
            'top_radicals' => $radicals
        ]);
    }

    // Быстрое редактирование поля
    public function quickUpdate(Request $request, Character $character)
    {
        $request->validate([
            'field' => 'required|in:definition,radical,stroke_count',
            'value' => 'nullable|string|max:255'
        ]);

        $character->update([$request->field => $request->value]);

        return response()->json([
            'status' => 'updated',
            'character' => $character
        ]);
    }
}
