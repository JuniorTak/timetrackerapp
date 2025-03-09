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
    @else
        <div class="p-6 text-gray-900">
            {{ __("No data to show.") }}
        </div>
    @endif
@endsection