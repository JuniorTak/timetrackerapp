<x-app-layout>
  <x-slot name="title">
    <title>TimeTrackerApp - @yield('title')</title>
  </x-slot>

  <x-slot name="head">
    @yield('css')
  </x-slot>

  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        @yield('header')
    </h2>
  </x-slot>
  
  <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="section">
          <div class="container">  
            @yield('content')
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>