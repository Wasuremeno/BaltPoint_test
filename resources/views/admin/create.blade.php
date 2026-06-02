<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Add Character</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        textarea {
            min-height: 100px;
        }

        .btn {
            padding: 8px 16px;
            background: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-secondary {
            background: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Add New Character</h2>
        <form action="{{ route('characters.store') }}" method="POST">
            @csrf
            <div class="form-group"><label>Character *</label><input type="text" name="character" required></div>
            <div class="form-group"><label>Pinyin (comma-separated)</label><input type="text" name="pinyin"
                    placeholder="nǐ, wo, ta"></div>
            <div class="form-group"><label>Definition</label><textarea name="definition"></textarea></div>
            <div class="form-group"><label>Radical</label><input type="text" name="radical"></div>
            <div class="form-group"><label>Decomposition</label><input type="text" name="decomposition"></div>
            <div class="form-group"><label>Etymology</label><textarea name="etymology"></textarea></div>
            <div class="form-group"><label>Stroke Count</label><input type="text" name="stroke_count"></div>
            <button type="submit" class="btn">Save</button>
            <a href="{{ route('characters.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>
