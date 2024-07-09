@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    {{ Auth::user()->name }}
    @role('Admin')
    <p>Welcome ADMIN</p>
    @endrole
    @role('Maitre')
    <p>Welcome MAITRE</p>
    @endrole
    @role('Mozo')
    <p>Welcome MOZO</p>
    @endrole
@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
    <script>
        
    </script>
@stop