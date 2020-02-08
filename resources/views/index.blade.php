@extends('layouts.front')
@section('content')
    <div class="main-container list-tasks">
        <div class="text-center loading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="tasks-content"></div>
    </div>
@endsection
