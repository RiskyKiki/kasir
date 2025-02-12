@extends('layouts.app')

@section('title', 'Dashboard')

@section('subtitle', 'Dashboard')

@section('content')
    <div class="section-body">
        <h2 class="section-title">Selamat datang di Dashboard, {{ Auth::user()->username }}</h2>
        <p>Silakan pilih menu di sidebar.</p>
    </div>
@endsection
