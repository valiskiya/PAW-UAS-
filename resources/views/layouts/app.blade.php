<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Ritel ABC')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Loading Indicator */
        .loading-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
            background-size: 200% 100%;
            animation: loading 1s ease-in-out infinite;
            z-index: 9999;
            display: none;
        }
        
        .loading-bar.active {
            display: block;
        }
        
        @keyframes loading {
            0% { background-position: 0% 0%; }
            100% { background-position: 200% 0%; }
        }
        
        /* Smooth Transitions */
        * {
            transition: background-color 0.1s ease, color 0.1s ease, transform 0.1s ease;
        }
        
        /* Fast Hover Effects */
        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .btn-hover:active {
            transform: translateY(0);
        }
        
        /* Instant Click Feedback */
        .clickable {
            cursor: pointer;
            user-select: none;
        }
        
        .clickable:active {
            opacity: 0.8;
            transform: scale(0.98);
        }
        
        /* Smooth Page Transitions */
        .page-transition {
            animation: fadeIn 0.15s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Skeleton Loading */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s ease-in-out infinite;
        }
        
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Optimize rendering */
        .gpu-accelerated {
            transform: translateZ(0);
            will-change: transform, opacity;
        }
        
        /* Instant Feedback for Forms */
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <!-- Loading Bar -->
    <div id="loadingBar" class="loading-bar"></div>
    
    <div class="min-h-screen" x-data="{ sidebarOpen: true }">
        <!-- Navbar -->
        <nav class="bg-blue-600 text-white shadow-lg fixed top-0 left-0 right-0 z-50">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Sidebar Toggle -->
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="mr-4 p-2 rounded-lg hover:bg-blue-700 clickable">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold clickable">
                            <i class="fas fa-store mr-2"></i>OneMart Retail
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-sm hidden md:block">
                            {{ auth()->user()->full_name }}
                            <span class="text-blue-200">({{ auth()->user()->role->display_name }})</span>
                        </span>
                        
                        <a href="{{ route('profile') }}" class="hover:text-blue-200 clickable p-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-user-circle text-xl"></i>
                        </a>
                        
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-blue-200 clickable p-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-sign-out-alt text-xl"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Main Container -->
        <div class="flex pt-16">
            <!-- Sidebar -->
            <aside x-show="sidebarOpen" 
                   x-transition:enter="transition ease-out duration-150"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in duration-150"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="-translate-x-full"
                   class="w-64 bg-white shadow-md fixed left-0 top-16 bottom-0 overflow-y-auto gpu-accelerated z-40">
                <div class="p-4">
                    @include('layouts.sidebar')
                </div>
            </aside>
            
            <!-- Main Content -->
            <main class="flex-1 p-6 page-transition gpu-accelerated"
                  :class="sidebarOpen ? 'ml-64' : 'ml-0'"
                  style="transition: margin-left 0.15s ease;">
                <!-- Alerts -->
                <div id="alertContainer">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 alert-fade">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 alert-fade">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('warning'))
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4 alert-fade">
                            <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('warning') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 alert-fade">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Fast Navigation Script -->
    <script>
        // Instant Page Loading with Prefetch
        document.addEventListener('DOMContentLoaded', function() {
            // Prefetch on hover
            const links = document.querySelectorAll('a[href^="/"]');
            links.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    const href = this.getAttribute('href');
                    if (href && !this.dataset.prefetched) {
                        const prefetch = document.createElement('link');
                        prefetch.rel = 'prefetch';
                        prefetch.href = href;
                        document.head.appendChild(prefetch);
                        this.dataset.prefetched = 'true';
                    }
                });
            });
            
            // Fast click handler
            document.addEventListener('click', function(e) {
                const target = e.target.closest('a[href^="/"]');
                if (target && !target.hasAttribute('target') && !e.ctrlKey && !e.metaKey) {
                    const href = target.getAttribute('href');
                    if (href && !href.includes('#')) {
                        e.preventDefault();
                        showLoadingBar();
                        window.location.href = href;
                    }
                }
            });
            
            // Show loading bar
            function showLoadingBar() {
                document.getElementById('loadingBar').classList.add('active');
            }
            
            // Auto-hide alerts after 3 seconds
            const alerts = document.querySelectorAll('.alert-fade');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.3s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 3000);
            });
            
            // Instant form submission feedback
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    showLoadingBar();
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        submitBtn.disabled = true;
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                        
                        // Re-enable after 5 seconds as fallback
                        setTimeout(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }, 5000);
                    }
                });
            });
            
            // Fast table row click
            document.querySelectorAll('table tbody tr').forEach(row => {
                const link = row.querySelector('a');
                if (link) {
                    row.style.cursor = 'pointer';
                    row.addEventListener('click', function(e) {
                        if (e.target.tagName !== 'A' && e.target.tagName !== 'BUTTON') {
                            link.click();
                        }
                    });
                }
            });
            
            // Instant search with debounce
            const searchInputs = document.querySelectorAll('input[type="search"], input[placeholder*="Cari"]');
            searchInputs.forEach(input => {
                let timeout;
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        const form = this.closest('form');
                        if (form) {
                            showLoadingBar();
                            form.submit();
                        }
                    }, 300); // Debounce 300ms
                });
            });
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K for quick search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[type="search"], input[placeholder*="Cari"]');
                if (searchInput) searchInput.focus();
            }
            
            // ESC to close modals
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('[id$="Modal"]');
                modals.forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                    }
                });
            }
        });
        
        // Optimize images loading
        if ('loading' in HTMLImageElement.prototype) {
            const images = document.querySelectorAll('img[data-src]');
            images.forEach(img => {
                img.src = img.dataset.src;
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>