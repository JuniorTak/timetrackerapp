@extends('template')
@section('title')
Reports
@endsection('title')
@section('css')
    <style>
        .card-footer {
            justify-content: center;
            align-items: center;
            padding: 0.4em;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">Reports</p>
        </header>
        <div class="card-content">
            <div class="content">
            <table class="table is-hoverable">
                    <thead>
                        <tr>
                            <th>Date of shift</th>
                            <th>User name</th>
                            <th>Active time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paginatedActivityTimes as $activityTime)
                            <tr>
                                <td>{{ $activityTime['shift_date'] }}</td>
                                <td>{{ $activityTime['user_name'] }}</td>
                                <td>{{ $activityTime['active_time'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <footer class="card-footer">
            {{ $paginatedActivityTimes->withPath(url()->current())->links() }}
        </footer>
    </div>
@endsection