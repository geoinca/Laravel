@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h2>Shows Object</h2>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">size</th>
                        <th scope="col">
                            @if (is_array($results) )
                            <form action="{{ route('process_fileupload_path') }}" method="post">
                             {{ csrf_field() }}
                             <button type="submit" class='btn btn-danger'>Procesar</button>
                            </form>
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (is_array($results) )
                    @foreach($results as  $id => $value  )
                        <tr>
                            <th scope="row">     {{ $id }}    </th>
                            <td>{{ $value["Key"] }}</td>
                            <td>{{ $value["Size"] }}</td>
                            <td>
                                <form action="{{ route('download_fileupload_path') }}" method="post">
                                    <input type="hidden"  id="filename" name="filename" value='{{ $value["Key"] }}'>
                                    {{ csrf_field() }}
                                    <button type="submit" class='btn btn-danger'>Download</button>
                                </form>
                            </td>
                        </tr>                      
                    @endforeach
                    @else
                        <tr> no hay datos                             </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>    
@endsection
