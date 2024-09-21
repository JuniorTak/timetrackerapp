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
            <div class="card-header">
                <p class="card-header-title">Reports</p>
                <div class="select">
                    <select onchange="window.location.href = this.value">
                        <option value="{{ route('reports.index') }}" @unless($id) selected @endunless>All users</option>
                        @foreach($users as $user)
                            <option value="{{ route('reports.user', $user->id) }}" {{ $id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
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
    @else
        <div class="p-6 text-gray-900">
            {{ __("No data to show.") }}
        </div>
    @endif
@endsection