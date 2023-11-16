@extends('template')
@section('css')

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
                            <th>08/2023</th>
                            <th>Users</th>
                            <th>Total active working</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ activityTime['date'] }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ activityTime[$user->id] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <footer class="card-footer">
        </footer>
    </div>
@endsection