<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    // Главная страница - HTML/CSS/JS просмотрщик
    public function viewer()
    {
        return view('viewer');
    }

    // API для получения данных (без React, обычный JSON)
    public function apiIndex(Request $request)
    {
        $query = Character::query();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where('character', 'like', "%{$term}%")
                ->orWhere('pinyin', 'like', "%{$term}%")
                ->orWhere('definition', 'like', "%{$term}%");
        }

        $characters = $query->orderBy('character')->get();

        return response()->json($characters->map(function ($c) {
            return [
                'char' => $c->character,
                'pinyin' => $c->pinyin_string,
                'def' => $c->definition,
                'radical' => $c->radical,
                'decomposition' => $c->decomposition,
                'etymology' => $c->etymology
            ];
        }));
    }

    // CRUD методы для администрирования
    public function index()
    {
        $characters = Character::orderBy('character')->paginate(50);
        return view('admin.index', compact('characters'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'character' => 'required|string|max:10|unique:characters',
            'pinyin' => 'nullable|string|max:255',
            'definition' => 'nullable|string',
            'radical' => 'nullable|string|max:50',
            'decomposition' => 'nullable|string',
            'etymology' => 'nullable|string',
            'stroke_count' => 'nullable|string|max:10',
        ]);

        if (!empty($validated['pinyin'])) {
            $validated['pinyin'] = explode(',', $validated['pinyin']);
            $validated['pinyin'] = array_map('trim', $validated['pinyin']);
        }

        Character::create($validated);

        return redirect()->route('characters.index')->with('success', 'Character created!');
    }

    public function edit(Character $character)
    {
        return view('admin.edit', compact('character'));
    }

    public function update(Request $request, Character $character)
    {
        $validated = $request->validate([
            'character' => 'required|string|max:10|unique:characters,character,' . $character->id,
            'pinyin' => 'nullable|string|max:255',
            'definition' => 'nullable|string',
            'radical' => 'nullable|string|max:50',
            'decomposition' => 'nullable|string',
            'etymology' => 'nullable|string',
            'stroke_count' => 'nullable|string|max:10',
        ]);

        if (!empty($validated['pinyin'])) {
            $validated['pinyin'] = explode(',', $validated['pinyin']);
            $validated['pinyin'] = array_map('trim', $validated['pinyin']);
        }

        $character->update($validated);

        return redirect()->route('characters.index')->with('success', 'Character updated!');
    }

    public function destroy(Character $character)
    {
        $character->delete();
        return redirect()->route('characters.index')->with('success', 'Character deleted!');
    }
}
