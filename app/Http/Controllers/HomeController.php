<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $storage = Storage::disk('minio');
       
        $client = $storage->getAdapter()->getClient();
        $command = $client->getCommand('ListObjects');
        $command['Bucket'] = $storage->getAdapter()->getBucket();
        //$command['Prefix'] = 'id' . $request->user()->id . '/';
        $result = $client->execute($command);
        //;$request->user()->id

        //return view('fileupload.index')->with(['results' => $result['Contents']]);
        return view('home')->with(['results' => $result['Contents']]);

    }
}
