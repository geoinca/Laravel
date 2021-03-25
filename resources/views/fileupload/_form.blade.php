
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @foreach(Session::get('image') as $img)
            <img src="images/{{ $img }}" style="width:100px;">
        @endforeach
    @endif

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your file.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<form class="m-2" method="post" action="{{ route('store_fileupload_path') }}" enctype="multipart/form-data">

    {{ csrf_field() }}

    <!-- Title Field -->
    <div class="form-group">
        {{--<label for="name">File Name</label>--}}
       
    </div>
    <div class="input-group-addon-objectup00">
        <label for="objectup">Choose Object</label>
        <input type="file" id="objectup" class="" name="objectup[]" multiple>
    </div>
    <button type="submit" class="btn btn-primary w-75 mx-auto">Upload</button>
</form>
