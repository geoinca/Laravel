<?php

namespace App\Http\Controllers;

use App\Models\Fileupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileuploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $storage = Storage::disk('minio');
       
        $client = $storage->getAdapter()->getClient();
        $command = $client->getCommand('ListObjects');
        $command['Bucket'] = $storage->getAdapter()->getBucket();
        //$command['Prefix'] = 'id' . $request->user()->id . '/';
        $result = $client->execute($command);
        //;$request->user()->id

        return view('fileupload.index')->with(['results' => $result['Contents']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        return view('fileupload.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $imageNameArr = [];
        foreach ($request->objectup as $file) {
            //$file = $request->file('objectup');
            $name=time().$file->getClientOriginalName();
            $imageNameArr[] = $name;
            //$filePath = '/' . 'id' . $request->user()->id. '/' . $name;
            $filePath = '/' . $name;
            Storage::disk('minio')->put($filePath, file_get_contents($file));

            //$txtmsg= $name.' Upload!';
        }
        exec("/usr/bin/argo submit --watch -n argo  https://raw.githubusercontent.com/geoinca/miniok/main/argo/hello-world10.yaml        ", );
    
        session()->flash('message', $name.' Upload!');
        //return redirect('/');
        return redirect()->route('fileupload_path');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fileupload  $fileupload
     * @return \Illuminate\Http\Response
     */
    public function show(Fileupload $fileupload)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fileupload  $fileupload
     * @return \Illuminate\Http\Response
     */
    public function edit(Fileupload $fileupload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fileupload  $fileupload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fileupload $fileupload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fileupload  $fileupload
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fileupload $fileupload)
    {
        //
    }
}
