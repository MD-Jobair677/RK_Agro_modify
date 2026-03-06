@extends('admin.layouts.master')
@section('master')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">

                    <a href="{{route('admin.category.index')}}"> index</a>
                        <a href="{{route('admin.category.edit')}}"> edit</a>
                        <a href="{{route('admin.category.delete')}}"> delete</a>

                        <h1> Edit Page</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection