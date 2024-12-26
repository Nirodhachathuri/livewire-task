@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Task List</h1>
        @livewire('task-table')
    </div>
@endsection
