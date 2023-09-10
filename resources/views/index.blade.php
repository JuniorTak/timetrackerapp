@extends('template')
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
        a.button {
            margin: 0.1em;
        }
        td.actions {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">Shifts</p>
            <div class="select">
                <select onchange="window.location.href = this.value">
                    <option value="{{ route('shifts.index') }}" @unless($id) selected @endunless>Tous les utilisateurs</option>
                    @foreach($users as $user)
                        <option value="{{ route('shifts.user', $user->id) }}" {{ $id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <a class="button is-info" href="#">Show reports</a>
        </header>
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
                                    <a class="button is-primary" href="{{ route('shifts.show', $shift->id) }}">Voir</a>
                                    <a class="button is-warning" href="{{ route('shifts.edit', $shift->id) }}">Modifier</a>
                                    <a class="button is-danger" href="#">Supprimer</a>
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
@endsection