<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sortir Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#06B6D4',
                        accent: '#F59E0B',
                        success: '#10B981',
                        danger: '#EF4444',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Default sidebar styles */
        .sidebar { width: 256px; }
        .main-content { margin-left: 256px; }
        
        /* Sidebar collapsed styles - NO transition by default */
        .sidebar-collapsed .sidebar { width: 80px; }
        .sidebar-collapsed .sidebar-text { display: none; }
        .sidebar-collapsed .main-content { margin-left: 80px; }
        .sidebar-collapsed .sidebar-header { 
            flex-direction: column; 
            gap: 0.75rem; 
            padding: 1rem 0.75rem;
            align-items: center;
        }
        .sidebar-collapsed .sidebar-nav { padding: 0.75rem; }
        .sidebar-collapsed .sidebar-nav a { 
            justify-content: center; 
            padding: 0.875rem;
            width: 100%;
        }
        .sidebar-collapsed .sidebar-label { display: none; }
        .sidebar-collapsed .sidebar-user { 
            justify-content: center; 
            padding: 1rem 0.75rem; 
        }
        .sidebar-collapsed .sidebar-user-info { display: none; }
        .sidebar-collapsed .toggle-arrow { transform: rotate(180deg); }
        
        /* Tooltip for collapsed sidebar */
        .sidebar-collapsed .nav-link:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            margin-left: 0.75rem;
            padding: 0.5rem 0.875rem;
            background: #1f2937;
            color: white;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* Animation class - only added when clicking toggle */
        .animate-sidebar .sidebar,
        .animate-sidebar .sidebar-text,
        .animate-sidebar .main-content,
        .animate-sidebar .toggle-arrow,
        .animate-sidebar .sidebar-header,
        .animate-sidebar .sidebar-nav,
        .animate-sidebar .sidebar-nav a {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="bg-gray-50" id="app">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="sidebar bg-white border-r border-gray-100 fixed h-full z-50 shadow-sm">
            <!-- Header -->
            <div class="sidebar-header flex items-center justify-between p-4 border-b border-gray-100">
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-200">S</div>
                    <span class="sidebar-text font-bold text-gray-800 text-lg">SORTIR</span>
                </div>
                <button onclick="toggleSidebar()" class="p-2.5 bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 rounded-xl shadow-lg shadow-blue-200 transition-all flex-shrink-0">
                    <svg class="toggle-arrow w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-nav p-4 space-y-1">
                <a href="/dashboard" data-tooltip="Dashboard"
                   class="nav-link relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->is('dashboard') ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <p class="sidebar-label sidebar-text px-4 pt-6 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</p>
                
                <a href="/categories" data-tooltip="Kategori"
                   class="nav-link relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->is('categories*') ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span class="sidebar-text">Kategori</span>
                </a>

                <a href="/items" data-tooltip="Barang"
                   class="nav-link relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->is('items*') ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span class="sidebar-text">Barang</span>
                </a>

                <p class="sidebar-label sidebar-text px-4 pt-6 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Aktivitas</p>

                <a href="/transactions" data-tooltip="Transaksi"
                   class="nav-link relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->is('transactions*') ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    <span class="sidebar-text">Transaksi</span>
                </a>
            </nav>

            <!-- User Section -->
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-100 bg-gray-50/50">
                <form method="POST" action="/logout" class="sidebar-user flex items-center gap-3">
                    @csrf
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-blue-200 flex-shrink-0">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="sidebar-user-info sidebar-text flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                        <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Logout</button>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content flex-1">
            <!-- Top Header -->
            <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 px-8 py-4 flex justify-between items-center sticky top-0 z-40">
                <h2 class="text-xl font-bold text-gray-800">@yield('title', 'Dashboard')</h2>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">{{ now()->format('l, d M Y') }}</span>
                </div>
            </header>

            <div class="p-8">
                @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Initialize sidebar state from localStorage - WITHOUT animation
        (function() {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                document.getElementById('app').classList.add('sidebar-collapsed');
            }
        })();

        function toggleSidebar() {
            const app = document.getElementById('app');
            
            // Add animation class before toggling
            app.classList.add('animate-sidebar');
            
            // Toggle collapsed state
            app.classList.toggle('sidebar-collapsed');
            
            // Save state to localStorage
            const isCollapsed = app.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
            
            // Remove animation class after transition completes
            setTimeout(() => {
                app.classList.remove('animate-sidebar');
            }, 350);
        }
    </script>
</body>
</html>