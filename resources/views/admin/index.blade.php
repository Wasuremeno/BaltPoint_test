<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Hanzi Manager</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }

        .btn:hover {
            opacity: 0.9;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f8f9fa;
        }

        tr:hover {
            background: #f5f5f5;
        }

        .flash {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .flash-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Исправленная пагинация */
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 5px;
            flex-wrap: wrap;
            list-style-type: none;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            background: white;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #2196F3;
            border-radius: 4px;
            font-size: 14px;
        }

        .pagination a:hover {
            background: #e3f2fd;
            border-color: #2196F3;
        }

        .pagination .active span {
            background: #2196F3;
            color: white;
            border-color: #2196F3;
        }

        /* Скрываем текстовый блок "Showing results" */
        .pagination .hidden.sm\:flex-1 {
            display: none !important;
        }

        .viewer-link {
            color: #2196F3;
            text-decoration: none;
            margin-left: 20px;
        }

        .char-cell {
            font-size: 24px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div>
                <strong>📖 Hanzi Admin Panel</strong>
                <a href="/" class="viewer-link">← Back to Viewer</a>
            </div>
            <a href="{{ route('characters.create') }}" class="btn">➕ Add Character</a>
        </div>

        @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Character</th>
                    <th>Pinyin</th>
                    <th>Definition</th>
                    <th>Radical</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($characters as $char)
                <tr>
                    <td class="char-cell">{{ $char->character }}</td>
                    <td>{{ $char->pinyin_string ?: '—' }}</td>
                    <td>{{ Str::limit($char->definition, 50) ?: '—' }}</td>
                    <td>{{ $char->radical ?: '—' }}</td>
                    <td>
                        <a href="{{ route('characters.edit', $char) }}" class="btn btn-secondary btn-sm">Edit</a>
                        <form action="{{ route('characters.destroy', $char) }}" method="POST" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No characters found. Run seeder first!</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">{{ $characters->links('pagination::bootstrap-4') }}</div>
    </div>
</body>

</html>
