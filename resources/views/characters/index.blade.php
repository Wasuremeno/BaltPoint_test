@extends('layouts.app')

@section('title', 'Hanzi Characters')

@section('content')
<div class="stats-bar">
    <div>
        <strong>Total:</strong> {{ $characters->total() }} characters
    </div>
    <div>
        <label style="display: inline-flex; align-items: center; gap: 5px; margin-right: 15px;">
            <input type="checkbox" id="showFavorites" {{ request('favorites')=='true' ? 'checked' : '' }}
                onchange="toggleFavorites()">
            ❤️ Only favorites
        </label>
        <a href="{{ route('characters.create') }}" class="btn btn-primary">➕ Add Character</a>
        <a href="{{ route('characters.export') }}" class="btn btn-secondary">📥 Export CSV</a>
    </div>
</div>

<form method="GET" class="search-bar">
    <input type="text" name="search" placeholder="🔍 Search by character, pinyin, or definition..."
        value="{{ request('search') }}">

    <select name="radical">
        <option value="">All radicals</option>
        @foreach($radicals as $rad)
        <option value="{{ $rad }}" {{ request('radical')==$rad ? 'selected' : '' }}>
            {{ $rad }}
        </option>
        @endforeach
    </select>

    <select name="tag">
        <option value="">All tags</option>
        @foreach($tags as $tag)
        <option value="{{ $tag->name }}" {{ request('tag')==$tag->name ? 'selected' : '' }}>
            {{ $tag->name }}
        </option>
        @endforeach
    </select>

    <select name="sort">
        <option value="character" {{ request('sort')=='character' ? 'selected' : '' }}>Sort by Character</option>
        <option value="radical" {{ request('sort')=='radical' ? 'selected' : '' }}>Sort by Radical</option>
        <option value="created_at" {{ request('sort')=='created_at' ? 'selected' : '' }}>Sort by Date</option>
    </select>

    <select name="direction">
        <option value="asc" {{ request('direction')=='asc' ? 'selected' : '' }}>Ascending</option>
        <option value="desc" {{ request('direction')=='desc' ? 'selected' : '' }}>Descending</option>
    </select>

    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="{{ route('characters.index') }}" class="btn btn-secondary">Reset</a>
</form>

<td>
    <thead>
        <tr>
            <th>★</th>
            <th>Character</th>
            <th>Pinyin</th>
            <th>Definition</th>
            <th>Radical</th>
            <th>Strokes</th>
            <th>Tags</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($characters as $char)
        <tr data-id="{{ $char->id }}">
            <td data-label="★">
                <button class="favorite-btn" data-id="{{ $char->id }}">
                    {{ in_array($char->id, $favoriteIds) ? '❤️' : '🤍' }}
                </button>
            </td>
            <td data-label="Character">
                <div class="char-display" style="font-size: 32px;">{{ $char->character }}</div>
            </td>
            <td data-label="Pinyin">{{ $char->pinyin_string ?: '—' }}</td>
            <td data-label="Definition">
                <span class="editable" data-field="definition" data-id="{{ $char->id }}">
                    {{ Str::limit($char->definition, 60) ?: '—' }}
                </span>
            </td>
            <td data-label="Radical">
                <span class="editable" data-field="radical" data-id="{{ $char->id }}">
                    {{ $char->radical ?: '—' }}
                </span>
            </td>
            <td data-label="Strokes">
                <span class="editable" data-field="stroke_count" data-id="{{ $char->id }}">
                    {{ $char->stroke_count ?: '—' }}
                </span>
            </td>
            <td data-label="Tags">
                <div class="tags-container" data-id="{{ $char->id }}">
                    @foreach($char->tags as $tag)
                    <span class="tag" style="background: {{ $tag->color }}; color: white;">
                        {{ $tag->name }}
                        <button class="remove-tag" data-char="{{ $char->id }}" data-tag="{{ $tag->id }}"
                            style="background: none; border: none; color: white; cursor: pointer; margin-left: 4px;">×</button>
                    </span>
                    @endforeach
                    <button class="add-tag-btn" data-id="{{ $char->id }}"
                        style="background: none; border: 1px dashed #ccc; border-radius: 12px; padding: 2px 6px; font-size: 11px; cursor: pointer;">+</button>
                </div>
            </td>
            <td data-label="Actions" class="action-buttons">
                <a href="{{ route('characters.show', $char) }}" class="btn btn-secondary btn-sm">View</a>
                <a href="{{ route('characters.edit', $char) }}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('characters.destroy', $char) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Delete {{ $char->character }}?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align: center; padding: 40px;">No characters found.</td>
        </tr>
        @endforelse
    </tbody>
    </table>

    <div class="pagination">
        {{ $characters->links() }}
    </div>
    @endsection

    @section('scripts')
    <script>
        function toggleFavorites() {
    const checked = document.getElementById('showFavorites').checked;
    const url = new URL(window.location.href);
    if (checked) {
        url.searchParams.set('favorites', 'true');
    } else {
        url.searchParams.delete('favorites');
    }
    window.location.href = url.toString();
}

document.querySelectorAll('.favorite-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
        const charId = this.dataset.id;
        try {
            const response = await fetch(`/api/characters/${charId}/favorite`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();
            this.textContent = data.favorited ? '❤️' : '🤍';
            if (document.getElementById('showFavorites')?.checked) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});

document.querySelectorAll('.editable').forEach(el => {
    el.addEventListener('click', async function() {
        const field = this.dataset.field;
        const charId = this.dataset.id;
        const currentValue = this.innerText === '—' ? '' : this.innerText;

        const newValue = prompt(`Edit ${field}:`, currentValue);
        if (newValue === null) return;

        try {
            const response = await fetch(`/api/characters/${charId}/quick`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ field, value: newValue })
            });
            const data = await response.json();
            this.innerText = data.character[field] || '—';
        } catch (error) {
            console.error('Error:', error);
        }
    });
});

document.querySelectorAll('.add-tag-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
        const charId = this.dataset.id;
        const tagName = prompt('Enter tag name:');
        if (!tagName) return;

        try {
            const response = await fetch(`/api/characters/${charId}/tag`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ tag_name: tagName })
            });
            const data = await response.json();
            location.reload();
        } catch (error) {
            console.error('Error:', error);
        }
    });
});

document.querySelectorAll('.remove-tag').forEach(btn => {
    btn.addEventListener('click', async function(e) {
        e.stopPropagation();
        const charId = this.dataset.char;
        const tagId = this.dataset.tag;

        try {
            const response = await fetch(`/api/characters/${charId}/tag/${tagId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            });
            location.reload();
        } catch (error) {
            console.error('Error:', error);
        }
    });
});
    </script>
    @endsection
