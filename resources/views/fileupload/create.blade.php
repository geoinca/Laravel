@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 justify-content-center">
            <h2>Image Upload</h2>
        </div>
        <div class="col-md-8">
        @include('fileupload._form')
        </div>
    </div>
</div>
@endsection
