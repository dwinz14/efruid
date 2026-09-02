@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @if($user->isSuperAdmin())
        @include('dashboard.super-admin')
    @elseif($user->isDirut())
        @include('dashboard.dirut')
    @elseif($user->isItStaff())
        @include('dashboard.it-staff')
    @else
        @include('dashboard.pemohon')
    @endif
@endsection