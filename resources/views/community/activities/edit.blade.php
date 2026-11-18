@extends('layouts.app')

@section('title', 'Edit Aktivitas')

@section('content')
@include('community.activities._form', [
    'heading' => 'Perbarui aktivitas',
    'action' => route('community.activities.update', $activity),
    'method' => 'PUT',
    'buttonLabel' => $activity->status_aktivitas === 'ditolak' ? 'Simpan dan ajukan ulang' : 'Simpan perubahan',
])
@endsection
