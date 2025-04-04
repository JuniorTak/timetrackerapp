<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-lg">
                        👋 Hello, <span class="font-semibold">{{ auth()->user()->name }}</span>
                        @if(!auth()->user()->is_admin)
                            <div class="sm:float-right">📅 Today: <span id="currentDate"></span></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!auth()->user()->is_admin)
        <div class="pb-4">
            <div class="flex flex-col items-center justify-center bg-gray-100">
                <div class="bg-white shadow-xl rounded-lg p-6 w-full max-w-2xl">
                    <!-- Greeting and Status -->
                    <div class="text-center mb-6">
                        <p class="text-xl font-semibold text-gray-800 text-center mb-2">Time Tracking</p>
                        <p class="relative left-[-2rem] text-lg font-medium text-gray-600 mb-2">
                            <span class="mr-1">⏰ Current time:</span> <span id="liveTime" class="absolute font-semibold text-gray-800"></span>
                        </p>
                        <p class="text-lg font-medium text-gray-600">
                            🟡 Status: <span id="shiftStatus" class="font-semibold text-gray-800">Off Shift</span>
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="grid grid-cols-1 gap-4 mb-6">
                        <button id="timeInBtn" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-lg shadow-md transition">⏳ Clock In</button>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <button id="pauseBtn" class="bg-yellow-400 cursor-not-allowed text-white font-semibold py-3 rounded-lg shadow-md transition" disabled>⏸️ Pause</button>
                        <button id="snoozeBtn" class="bg-blue-400 cursor-not-allowed text-white font-semibold py-3 rounded-lg shadow-md transition" disabled>😴 Snooze</button>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <button id="timeOutBtn" class="bg-red-500 cursor-not-allowed text-white font-semibold py-3 rounded-lg shadow-md transition" disabled>🚪 Clock Out</button>
                    </div>

                    <!-- Status Display -->
                    <div class="mt-6 text-center">
                        <p id="statusMessage" class="text-gray-600 text-lg font-medium"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="pb-4">
            <div class="flex flex-col items-center justify-center bg-gray-100">
                <div class="bg-white shadow-xl rounded-lg p-6 w-full max-w-2xl">
                    <div class="text-center">
                        <p class="text-lg font-semibold text-gray-800 text-center mb-2">🕒 Shift Activity History</p>
                        <ul id="shiftHistory" class="text-lg font-medium text-gray-600">
                            <li>Loading shift history...</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
