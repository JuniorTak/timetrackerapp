@extends('template')

@section('title')
Reports
@endsection('title')

@section('header')
Reports
@endsection('header')

@section('content')
    @if (count($paginatedActivityTimes) > 0)
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="flex flex-wrap items-center justify-between px-4 py-3 border-b">
                <p class="text-lg font-semibold">Reports</p>
                <!-- Filter form -->
                <form method="GET" action="{{ url()->current() }}" class="max-w-xl m-2">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-2">
                            <label for="start_date">From</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}">
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="end_date">To</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}">
                        </div>
                        <div class="flex items-center">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Filtrer</button>
                        </div>
                    </div>
                </form>
                <div class="flex items-center m-2">
                    <a href="#chart" class="border border-blue-500 text-blue-500 hover:bg-blue-100 font-semibold px-4 py-2 rounded">View Chart 📊</a>
                </div>
                <div class="flex items-center gap-2 m-2">
                    <label for="selected-user">For</label>
                    <select id="selected-user" class="border rounded py-1 font-semibold" onchange="window.location.href = this.value">
                        @php
                        $queryParams = request()->query();
                        // Remove pagination-related parameters.
                        unset($queryParams['page'], $queryParams['per_page']);
                        @endphp
                        <option value="{{ route('reports.index', $queryParams) }}" @unless($id) selected @endunless>All users</option>
                        @foreach($users as $user)
                            <option value="{{ route('reports.user', array_merge(['id' => $user->id], $queryParams)) }}" {{ $id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="border px-4 py-2">Date of shift</th>
                                @if(!$id)<th class="border px-4 py-2"> User name</th>@endif
                                <th class="border px-4 py-2">Active time</th>
                                <th class="border px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paginatedActivityTimes as $activityTime)
                                <tr class="hover:bg-gray-50">
                                    <td class="border px-4 py-2">{{ $activityTime['shift_date'] }}</td>
                                    @if(!$id)<td class="border px-4 py-2">{{ $activityTime['user_name'] }}</td>@endif
                                    <td class="border px-4 py-2">{{ $activityTime['active_time'] }}</td>
                                    <td class="border px-4 py-2 flex justify-center">
                                        <a class="border border-blue-500 text-blue-500 hover:bg-blue-100 px-3 py-1 rounded" href="#">
                                            <span class="mx-2">👁️‍🗨️</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <footer class="flex items-center justify-center px-4 py-3 border-t text-gray-700">
                {{ $paginatedActivityTimes->withPath(url()->current())->withQueryString()->links() }}
            </footer>
        </div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mt-6">
            <div class="px-4 py-3 border-b">
                <p id="chart" class="text-lg font-semibold">Activity time chart</p>
            </div>
            <div class="p-4">
                <div class="content">
                    <canvas id="workTimeChart"></canvas>
                </div>
            </div>
        </div>
    @else
        <div class="p-6 text-gray-900">
            {{ __("No data to show.") }}
        </div>
    @endif
    
    @php
    $chartData = [];

    if($id) {
        // Compute total activity time for a single user.
        $sum = 0;
        foreach ($activityTimes as $activityTime) {
            if ($activityTime['user_id'] == $id) {
                $carbonTime = \Carbon\Carbon::createFromFormat('H:i:s', $activityTime['active_time']);
                $totalHours = $carbonTime->hour + ($carbonTime->minute / 60) + ($carbonTime->second / 3600);
                $sum += $totalHours;
            }
        }
        $chartData = [$sum];
    } else {
        // Compute total activity time for all.
        $chartData = $users->map(function ($user) use ($activityTimes) {
            $sum = 0;
            foreach ($activityTimes as $activityTime) {
                if ($activityTime['user_id'] == $user->id) {
                    $carbonTime = \Carbon\Carbon::createFromFormat('H:i:s', $activityTime['active_time']);
                    $totalHours = $carbonTime->hour + ($carbonTime->minute / 60) + ($carbonTime->second / 3600);
                    $sum += $totalHours;
                }
            }
            return $sum;
        })->toArray();
    }
    @endphp
    <script type="module">
        const ctx = document.getElementById('workTimeChart').getContext("2d");
        const workTimeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($id ? [$users->pluck('name', 'id')->get($id)] : $users->pluck('name')),
                datasets: [{
                    label: 'Total activity time (in hours)',
                    data: @json($chartData),
                    backgroundColor: 'rgba(75, 192, 192, 1)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    maxBarThickness: 300
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection