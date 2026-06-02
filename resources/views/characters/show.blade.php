@extends('layouts.app')

@section('title', $character->character)

@section('content')
<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap;">
        <div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="font-size: 96px; font-family: 'Noto Sans CJK SC', sans-serif;">
                    {{ $character->character }}
                </div>
                <button class="favorite-btn" data-id="{{ $character->id }}"
                    style="background: none; border: none; cursor: pointer; font-size: 32px;">
                    {{ $favorited ? '❤️' : '🤍' }}
                </button>
            </div>
            <p><strong>Pinyin:</strong> {{ $character->pinyin_string ?: '—' }}</p>
            <p><strong>Definition:</strong> {{ $character->definition ?: '—' }}</p>
            <p><strong>Radical:</strong> {{ $character->radical ?: '—' }}</p>
            <p><strong>Decomposition:</strong> {{ $character->decomposition ?: '—' }}</p>
            <p><strong>Stroke Count:</strong> {{ $character->stroke_count ?: '—' }}</p>
            <p><strong>Etymology:</strong> {{ $character->etymology ?: '—' }}</p>
            <p><strong>Tags:</strong>
                @forelse($character->tags as $tag)
                <span class="tag" style="background: {{ $tag->color }}; color: white;">{{ $tag->name }}</span>
                @empty
                —
                @endforelse
            </p>
            <p><small><strong>Created:</strong> {{ $character->created_at ?: '—' }}</small></p>
            <p><small><strong>Updated:</strong> {{ $character->updated_at ?: '—' }}</small></p>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('characters.edit', $character) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('characters.destroy', $character) }}" method="POST"
                onsubmit="return confirm('Delete {{ $character->character }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
            <a href="{{ route('characters.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelector('.favorite-btn')?.addEventListener('click', async function() {
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
    } catch (error) {
        console.error('Error:', error);
    }
});
</script>
@endsection
