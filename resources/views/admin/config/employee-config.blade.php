@extends('layouts/contentNavbarLayout')

@section('title', 'Config')

@section('content')
<div class="row">
  <div class="col-xxl">
    @livewire('admin.employee-config')
  </div>
</div>
@endsection
