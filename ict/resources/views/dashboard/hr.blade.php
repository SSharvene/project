@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold">Dashboard (HR)</h1>
    <p class="mt-3 text-gray-600">Hello, {{ auth()->user()->full_name ?? auth()->user()->name ?? auth()->user()->email }}</p>
</div>
@endsection
