@extends('layouts.backend.chatbox')
@section('content')
    
        <chatbox :current-user="{{ auth()->user() }}" :current-profile="{{ auth()->user()->profile }}"></chatbox>
    
@endsection
