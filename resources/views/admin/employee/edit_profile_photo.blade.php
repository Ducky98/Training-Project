@extends('layouts/contentNavbarLayout')

@section('title', 'Update Photo')

@section('page-script')
@endsection

@section('content')

  <x-photo-upload
    :upload-route="route('admin.employee.update_profile_photo', $employee->id)"
    :existing-file="$employee->avatar"
    input-name="avatar"
    label="Profile Photo"
    :is-avatar="true"
  />


@endsection
