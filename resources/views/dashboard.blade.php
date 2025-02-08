@extends('layouts.app')

@section('content')
<h1>Selamat datang di Dashboard, {{ Auth::user()->username }}</h1>
<p>Silakan pilih menu di sidebar.</p>
@endsection
