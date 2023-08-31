@extends('layouts.admin')
@section('title')
    MainPage
@endsection
@section('contentheader')
    MainPage
@endsection
@section('contentheaderlink')
    <a href="{{route('admin.dashboard')}}">MainPage</a>
@endsection
@section('contentheaderactive')
    View
@endsection
@section('content')
<div class="row" style="background-image: url({{asset('assets/admin/imgs/cover.png')}});background-size: cover;background-repeat: no-repeat;min-height: 600px"></div>
    
@endsection