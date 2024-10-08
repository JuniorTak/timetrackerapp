@extends('template')
@section('title')
Shifts
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
        td.actions {
            text-align: center;
        }
    </style>
@endsection
@section('header')
Shifts
@endsection('header')
@section('content')
    @if (count($shifts) > 0)
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">Shifts</p>
                <div class="select">
                    <select onchange="window.location.href = this.value">
                        <option value="{{ route('shifts.index') }}" @unless($id) selected @endunless>All users</option>
                        @foreach($users as $user)
                            <option value="{{ route('shifts.user', $user->id) }}" {{ $id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <a class="button is-info" href="{{ $id ? route('reports.user', $id) : route('reports.index') }}">Show reports</a>
            </div>
            <div class="card-content">
                <div class="content">
                    <table class="table is-hoverable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shifts as $shift)
                                <tr>
                                    <td></td>
                                    <td>{{ $shift->the_date }}</td>
                                    <td>{{ $shift->time_in }}</td>
                                    <td>{{ $shift->time_out }}</td>
                                    <td>{{ $shift->user->name }}</td>
                                    <td class='actions'>
                                        <a class="button is-primary" href="{{ route('shifts.show', $shift->id) }}">Show</a>
                                        <a class="button is-warning" href="{{ route('shifts.edit', $shift->id) }}">Edit</a>
                                        <a class="button is-danger" href="#">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <footer class="card-footer">
                {{ $shifts->links() }}
            </footer>
        </div>
    @else
        <div class="p-6 text-gray-900">
            {{ __("No data to show.") }}
        </div>
    @endif
@endsection