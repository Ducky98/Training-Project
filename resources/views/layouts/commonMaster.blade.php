<!DOCTYPE html>

<html class="light-style layout-menu-fixed" data-theme="theme-default" data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="vertical-menu-laravel-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>@yield('title') | RRR </title>
  <meta name="description" content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
  <meta name="keywords" content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="manifest" href="/manifest.webmanifest">
  <meta name="theme-color" content="#007bff">


  <!-- Canonical SEO -->
  <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script>
    window.baseURL = "{{ url('/') }}";
  </script>
  <!-- Include Styles -->
  @include('layouts/sections/styles')
  @livewireStyles


  <!-- Include Scripts for customizer, helper, analytics, config -->
  @include('layouts/sections/scriptsIncludes')


  @stack('style')

</head>

<body>
    {{-- Success Alert --}}
    @if(session('success'))
      <x-alert type="success" :message="session('success')" />
    @endif

    {{-- General Errors (except validation errors) --}}
    @if(session('error') && !session('success'))
      <x-alert type="danger" :message="session('error')"/>
    @endif
  <!-- Layout Content -->
  @yield('layoutContent')
  <!--/ Layout Content -->


  <!-- Include Scripts -->
  @include('layouts/sections/scripts')
  @livewireScripts
    @stack('scripts')
</body>

</html>
