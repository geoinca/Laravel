@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h2>Upload Object</h2>
        <div class="col-md-8">
            @include('layouts.upload_form')
        </div>
    </div>
</div>        
@endsection
