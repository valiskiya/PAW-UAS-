<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Ritel ABC</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-500 to-blue-700 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">OneMart Retail</h1>
            <p class="text-gray-600 mt-2">Retail Product Rumahan</p>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required autofocus>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                Login
            </button>
        </form>
        
        <!--<div class="mt-6 border-t pt-6">
            <p class="text-sm text-gray-600 text-center">Demo Accounts:</p>
            <div class="grid grid-cols-2 gap-2 mt-2 text-xs">
                <div class="bg-gray-50 p-2 rounded">
                    <strong>Direktur:</strong> direktur / password123
                </div>
                <div class="bg-gray-50 p-2 rounded">
                    <strong>Manajer:</strong> manajer / password123
                </div>
                <div class="bg-gray-50 p-2 rounded">
                    <strong>Kasir:</strong> kasir1 / password123
                </div>
                <div class="bg-gray-50 p-2 rounded">
                    <strong>Logistik:</strong> logistik / password123
                </div>
            </div>
        </div>-->
    </div>
</body>
</html>