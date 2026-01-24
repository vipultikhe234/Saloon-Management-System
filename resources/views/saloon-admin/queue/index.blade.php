@extends('layouts.dashboard')

@section('title', 'Live Queue Management')
@section('page-title', 'Live Queue')
@section('page-subtitle', 'Manage waiting customers in real-time')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Active Queue -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex justify-between items-center">
                <span><i class="fas fa-users text-indigo-500 mr-2"></i>Waiting Queue</span>
                <span class="bg-indigo-100 text-indigo-800 text-sm px-3 py-1 rounded-full">{{ $queue->count() }} Waiting</span>
            </h3>

            @if($queue->count() > 0)
                <div class="space-y-4">
                    @foreach($queue as $apt)
                        <div class="border rounded-xl p-4 flex items-center justify-between {{ $apt->status == 'in_progress' ? 'bg-indigo-50 border-indigo-200' : 'bg-white' }} hover:shadow-md transition">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 {{ $apt->status == 'in_progress' ? 'bg-indigo-600' : 'bg-gray-200' }} rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    {{ $apt->token_number }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800 text-lg">{{ $apt->user->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $apt->service->name }} ({{ $apt->service->duration_minutes }}m)
                                    </div>
                                    @if($apt->status == 'in_progress')
                                        <div class="text-xs text-indigo-600 font-bold mt-1 uppercase animate-pulse">Now Serving</div>
                                    @else
                                        <div class="text-xs text-gray-400 mt-1">
                                            Est. Wait: {{ $loop->index * 20 }} mins
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                @if($apt->status == 'pending')
                                    <form action="{{ route('saloon-admin.queue.start', $apt->id) }}" method="POST">
                                        @csrf
                                        <button class="btn-sm bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-lg font-bold">
                                            Call
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('saloon-admin.queue.done', $apt->id) }}" method="POST">
                                    @csrf
                                    <button class="btn-sm bg-green-600 text-white hover:bg-green-700 px-4 py-2 rounded-lg font-bold">
                                        DONE
                                    </button>
                                </form>
                                
                                <form action="{{ route('saloon-admin.queue.cancel', $apt->id) }}" method="POST" onsubmit="return confirm('Remove from queue?');">
                                    @csrf
                                    <button class="text-gray-400 hover:text-red-500 px-2" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-mug-hot text-4xl mb-2"></i>
                    <p>No waiting customers.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- History -->
    <div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Today's History</h3>
            
            <div class="space-y-3">
                @forelse($history as $apt)
                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded transition">
                        <div class="flex items-center gap-3">
                            <div class="text-gray-400 font-mono text-sm">#{{ $apt->token_number }}</div>
                            <div>
                                <div class="font-semibold text-gray-700">{{ $apt->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $apt->updated_at->format('h:i A') }}</div>
                            </div>
                        </div>
                        <span class="text-xs font-bold {{ $apt->status == 'completed' ? 'text-green-600' : 'text-red-500' }} uppercase">
                            {{ $apt->status }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-400 text-center text-sm py-4">No completed tasks yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
