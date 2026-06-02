<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hanzi Manager')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f5f5f5;
            line-height: 1.5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: white;
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
        }

        .flash-message {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .flash-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .flash-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #007bff;
            border-radius: 4px;
        }

        .pagination .active span {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-bar input,
        .search-bar select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-bar input {
            flex: 1;
            min-width: 200px;
        }

        .char-display {
            font-size: 48px;
            font-family: 'Noto Sans CJK SC', 'Noto Sans CJK TC', 'Noto Sans CJK KR', 'Noto Sans CJK JP', 'Segoe UI Emoji', sans-serif;
            margin-bottom: 10px;
        }

        .stats-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tag {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 11px;
            margin: 2px;
        }

        .editable {
            cursor: pointer;
            border-bottom: 1px dashed #ccc;
        }

        .editable:hover {
            background: #fff3cd;
        }

        .favorite-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
        }

        @media (max-width: 768px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead {
                display: none;
            }

            td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #eee;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                flex: 1;
            }

            .action-buttons {
                justify-content: flex-end;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <div class="header">
        <div class="container">
            <h1>📖 Hanzi Character Manager</h1>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
        <div class="flash-message flash-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="flash-message flash-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    @yield('scripts')
</body>

</html>
