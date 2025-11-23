@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Log Aktivitas Sistem</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-4">
            <input type="text" placeholder="Cari log..." 
                   class="w-full px-4 py-2 border rounded-lg">
        </div>
        
        <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm h-96 overflow-y-auto">
            <div class="space-y-1">
                <div>[{{ date('Y-m-d H:i:s') }}] INFO: System started</div>
                <div>[{{ date('Y-m-d H:i:s') }}] INFO: User 'admin' logged in</div>
                <div>[{{ date('Y-m-d H:i:s') }}] INFO: Database backup created</div>
                <div>[{{ date('Y-m-d H:i:s') }}] INFO: Transaction TRX20250101001 created</div>
                <div class="text-yellow-400">[{{ date('Y-m-d H:i:s') }}] WARNING: Low stock alert for product PRD001</div>
                <div>[{{ date('Y-m-d H:i:s') }}] INFO: Purchase order PO20250101001 received</div>
                <div>[{{ date('Y-m-d H:i:s') }}] INFO: Salary calculated for 5 employees</div>
                <div class="text-gray-500">--- End of logs ---</div>
            </div>
        </div>
        
        <div class="mt-4 text-sm text-gray-600">
            <p>Log sistem menampilkan aktivitas penting yang terjadi di aplikasi.</p>
        </div>
    </div>
</div>
@endsection