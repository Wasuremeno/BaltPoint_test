@extends('layouts.app')

@section('title', 'Add Character')

@section('content')
<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h2 style="margin-bottom: 20px;">Add New Character</h2>

    <form action="{{ route('characters.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Character *</label>
            <input type="text" name="character" value="{{ old('character') }}" required maxlength="10">
            @error('character') <small style="color: red;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Pinyin (comma-separated)</label>
            <input type="text" name="pinyin" value="{{ old('pinyin') }}" placeholder="e.g., nǐ, wo, ta">
            <small>Separate multiple pronunciations with commas</small>
        </div>

        <div class="form-group">
            <label>Definition</label>
            <textarea name="definition">{{ old('definition') }}</textarea>
        </div>

        <div class="form-group">
            <label>Radical</label>
            <input type="text" name="radical" value="{{ old('radical') }}">
        </div>

        <div class="form-group">
            <label>Decomposition</label>
            <input type="text" name="decomposition" value="{{ old('decomposition') }}">
        </div>

        <div class="form-group">
            <label>Etymology</label>
            <textarea name="etymology">{{ old('etymology') }}</textarea>
        </div>

        <div class="form-group">
            <label>Stroke Count</label>
            <input type="text" name="stroke_count" value="{{ old('stroke_count') }}">
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Save Character</button>
            <a href="{{ route('characters.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
