@extends('template')
@section('title')
Reports
@endsection('title')
@section('css')
    <style>
        html {
            scroll-behavior: smooth;
        }
        .card-footer {
            justify-content: center;
            align-items: center;
            padding: 0.4em;
        }
        .is-margin-left-negative {
            margin-left: -1em;
        }
        .select, .is-info {
            margin: 0.3em;
        }
    </style>
@endsection
@section('header')
Reports
@endsection('header')
@section('content')
    @if (count($paginatedActivityTimes) > 0)
        <div class="card">
            <div class="card-header is-flex flex-wrap">
                <p class="card-header-title">Reports</p>
                <!-- Filter form -->
                <form method="GET" action="{{ url()->current() }}" class="">
                    <div class="columns gap-6">
                        <div class="column is-one-third is-flex is-align-items-center gap-2">
                            <label for="start_date">From</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}">
                        </div>
                        <div class="column is-one-third is-flex is-align-items-center gap-2">
                            <label for="end_date">To</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}">
                        </div>
                        <div class="column is-one-third is-flex is-align-items-center is-margin-left-negative">
                            <button type="submit" class="button is-info">Filtrer</button>
                        </div>
                    </div>
                </form>
                <div class="is-flex is-align-items-center ml-2 mr-6">
                    <a href="#chart" class="button is-info is-outlined has-text-weight-semibold">View Chart 📊</a>
                </div>
                <div class="is-flex is-align-items-center gap-2 ml-2">
                    <label for="selected-user" class="is-flex is-align-items-center">For</label>
                    <div class="select">
                        <select id="selected-user" class="form-select has-text-weight-semibold" onchange="window.location.href = this.value">
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
            </div>
            <div class="card-content">
                <div class="content">
                    <table class="table is-hoverable">
                        <thead>
                            <tr>
                                <th>Date of shift</th>
                                @if(!$id)<th> User name</th>@endif
                                <th>Active time</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paginatedActivityTimes as $activityTime)
                                <tr>
                                    <td>{{ $activityTime['shift_date'] }}</td>
                                    @if(!$id)<td>{{ $activityTime['user_name'] }}</td>@endif
                                    <td>{{ $activityTime['active_time'] }}</td>
                                    <td class='actions is-flex is-justify-content-center'>
                                        <a class="button is-link is-outlined" href="#">
                                            <span class="icon mx-2">👁️‍🗨️</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <footer class="card-footer">
                {{ $paginatedActivityTimes->withPath(url()->current())->withQueryString()->links() }}
            </footer>
        </div>
        <div class="card mt-6">
            <div class="card-header">
                <p id="chart" class="card-header-title">Activity time chart</p>
            </div>
            <div class="card-content">
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
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <script>
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