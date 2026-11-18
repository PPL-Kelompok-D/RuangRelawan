@extends('layouts.app')

@section('title', 'Buat Aktivitas')

@section('content')
@include('community.activities._form', [
    'activity' => null,
    'heading' => 'Buat aktivitas baru',
    'action' => route('community.activities.store'),
    'method' => 'POST',
    'buttonLabel' => 'Kirim untuk verifikasi',
])
@endsection
