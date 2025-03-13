@extends('template')

@section('title')
Shifts
@endsection('title')

@section('header')
Shifts
@endsection('header')

@section('content')
    @if (count($shifts) > 0)
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="sm:flex sm:items-center sm:justify-between px-4 py-3 border-b">
                <p class="text-lg font-semibold">Shifts</p>
                <div>
                    <select class="border rounded py-1" onchange="window.location.href = this.value">
                        <option value="{{ route('shifts.index') }}" @unless($id) selected @endunless>All users</option>
                        @foreach($users as $user)
                            <option value="{{ route('shifts.user', $user->id) }}" {{ $id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <a class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" href="{{ $id ? route('reports.user', $id) : route('reports.index') }}">Show reports</a>
                </div>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="border px-4 py-2">Date</th>
                                <th class="border px-4 py-2">Time In</th>
                                <th class="border px-4 py-2">Time Out</th>
                                <th class="border px-4 py-2">Name</th>
                                <th class="border px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shifts as $shift)
                                <tr class="hover:bg-gray-50">
                                    <td class="border px-4 py-2">{{ $shift->the_date }}</td>
                                    <td class="border px-4 py-2">{{ $shift->time_in }}</td>
                                    <td class="border px-4 py-2">{{ $shift->time_out }}</td>
                                    <td class="border px-4 py-2">{{ $shift->user->name }}</td>
                                    <td class="border px-4 py-2 flex flex-wrap justify-center gap-2">
                                        <a class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded" href="{{ route('shifts.show', $shift->id) }}">Show</a>
                                        <a class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded" href="{{ route('shifts.edit', $shift->id) }}">Edit</a>
                                        <a class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded" href="#">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <footer class="flex items-center justify-center px-4 py-3 border-t text-gray-700">
                {{ $shifts->links() }}
            </footer>
        </div>
    @else
        <div class="p-6 text-gray-900">
            {{ __("No data to show.") }}
        </div>
    @endif
@endsection