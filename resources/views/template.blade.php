<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TimeTrackerApp - @yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}" />
    <link rel="icon" href="{{ URL::asset('favicon.ico') }}" />
    @yield('css')
  </head>
  <body>
    <nav>
      <ul>
        <li>
        	<a href="{{ url('/') }}" title="Goto homepage">Home</a>
        </li>
        <li>
        	<a href="{{ route('shifts.index') }}" title="View all shifts">Shifts</a>
        </li>
      </ul>
    </nav>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
          <main class="section">
            <div class="container">  
              @yield('content')
            </div>
          </main>
        </div>
      </div>
    </div>
  </body>
</html>