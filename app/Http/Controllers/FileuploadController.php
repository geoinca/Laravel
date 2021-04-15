<?php

namespace App\Http\Controllers;

use App\Models\Fileupload;
use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

 

class FileuploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        
        $process  = new Process;
        $process->url= 'url';
        $process->user_id = $request->user()->id;
        $process->save();
        $processid = $process->id;
        foreach ($request->objectup as $file) {
            //$file = $request->file('objectup');
            $prename='ID'.$request->user()->id.':'.$processid.'P'.time();
            $name=$prename.$file->getClientOriginalName();
            $imageNameArr[] = $name;
            //$filePath = '/' . 'id' . $request->user()->id. '/' . $name;
            $filePath = '/' . $name;
            Storage::disk('minio')->put($filePath, file_get_contents($file));

            //$txtmsg= $name.' Upload!';
            $fileupload  = new Fileupload;
            $fileupload->user_id = $request->user()->id;
            $fileupload->name = $name;
            $fileupload->url = 'url';
            $fileupload->process_id =$processid;
            $fileupload->save();

        }
        
        //var_dump($imageNameArr) "{\"message\":\"this is my first webhook\"}"
        $url = 'http://webhook-eventsource-svc.argo-events:12000/example4';
        $post=['filaname'=>$prename];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            print_r(json_decode($response));
        }
           //dd($response);
    
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
    public function process(Fileupload $fileupload)
    {
        //
        //exec("/usr/bin/argo submit --watch -n argo  https://raw.githubusercontent.com/geoinca/miniok/main/argo/hello-world10.yaml", );

        //var_dump($imageNameArr) "{\"message\":\"this is my first webhook\"}"
        $url = 'http://webhook-eventsource-svc.argo-events:12000/example4';
        $post=['filaname'=>'this is my first webhook'];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            print_r(json_decode($response));
        }
           dd($response);
    
        session()->flash('message', $responsex.' Upload!');
        //return redirect('/');
        return redirect()->route('fileupload_path');
    }
}
