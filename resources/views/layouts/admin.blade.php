<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') - LSP-PIE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <!-- Trix Editor -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
        body {
            font-family: 'Public Sans', sans-serif;
        }
        /* Trix Editor Custom Styles */
        trix-toolbar .trix-button-group {
            margin-bottom: 0;
        }
        trix-editor {
            min-height: 300px;
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 1rem;
        }
        trix-editor:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }
        trix-toolbar {
            border: 1px solid #d1d5db;
            border-bottom: none;
            border-radius: 0.5rem 0.5rem 0 0;
            background: #f9fafb;
            padding: 0.5rem;
        }
        .trix-content {
            line-height: 1.6;
        }
        .trix-content h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }
        .trix-content ul, .trix-content ol {
            padding-left: 1.5rem;
            margin: 1rem 0;
        }
        .trix-content a {
            color: #3b82f6;
            text-decoration: underline;
        }
    </style>
    @yield('extra_css')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-admin.sidebar :active="$active ?? 'dashboard'" />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <x-admin.header />

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto px-4 py-4 lg:px-8 lg:py-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Trix Editor JS -->
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    @yield('extra_js')
</body>
</html>
