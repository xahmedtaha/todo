@extends('layouts.app')
@section('title', 'Tasks')

@section('content')
<div id="app"></div>
@endsection

@push('js')
    <script>
        window.User = @js(auth()->user())
    </script>
    @vite('resources/js/app.js')
@endpush
