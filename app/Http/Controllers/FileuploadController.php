<?php

namespace App\Http\Controllers;

use App\Models\Fileupload;
use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    public function index(Request $request)
    {
        //
        $storage = Storage::disk('minioout');
       
        $client = $storage->getAdapter()->getClient();
        $command = $client->getCommand('ListObjects');
        $command['Bucket'] ='outfolder';
        $command['Prefix'] = 'id' . $request->user()->id . '/';
        $result = $client->execute($command);
        $cart = array();

            foreach($result['Contents'] as  $id => $value  )
            {
                $cart[]= array("Key" => $value["Key"],"Size" =>$value["Size"],"KeyEnco" =>base64_encode($value["Key"]));
            }

        return view('fileupload.index')->with(['results' => $cart]);
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

            $prename='id'.$request->user()->id.'-'.$processid.'P'.time();
            $name=$prename.$file->getClientOriginalName();
            $imageNameArr[] = $name;

            $filePath = 'id'.$request->user()->id.'/' . $name;
            Storage::disk('minio')->put($filePath, file_get_contents($file));

            $fileupload  = new Fileupload;
            $fileupload->user_id = $request->user()->id;
            $fileupload->name = $name;
            $fileupload->url = 'url';
            $fileupload->process_id =$processid;
            $fileupload->save();

        }

        $url = 'http://webhook-eventsource-svc.argo-events:12000/example4';
        $post='{"s3prefix":"id'.$request->user()->id.'/","filename":"icantbelive'.$processid.'.jpg"}';
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        session()->flash('message', $name.' Upload!');
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
        //webhook-eventsource-svc.argo-events
    	// $response = Http::post('http://webhook-eventsource-svc.argo-events:12000/example4', [
        //     'filaname' => 'ThisistestfromHDTuto'
        // ]);
        //'{"filename":"icantbelive"}
        //$post=['filaname'=>'ThisistestfromHDTuto34'];
        $post='{"filename":"id34/icantbelive56.jpg"}';
        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, "http://webhook-eventsource-svc.argo-events:12000/example4");
        curl_setopt($cliente, CURLOPT_HEADER, true);
        curl_setopt($cliente, CURLOPT_POSTFIELDS, $post);
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, 1);
        $respuesta = curl_exec($cliente);
        curl_close($cliente);
        $respuesta = explode("\n\r\n", $respuesta);
    }
    public function download(Request $request)
    {
        $filename = $request->input('filename');
        $exists = Storage::disk('minio')->exists($filename);
        if($exists){
            $mime = Storage::disk('minio')->getDriver()->getMimetype($filename);
            $size = Storage::disk('minio')->getDriver()->getSize($filename);
            $headers =  [
                'Content-Type' => $mime,
                'Content-Length' => $size,
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename={$filename}",
                'Content-Transfer-Encoding' => 'binary',
              ];

              //ob_end_clean();
              return   \Response::make(Storage::disk('minio')->get($filename), 200, $headers);
        }
        else{
            dd($exists);
        }
   //$file = Storage::disk('minio')->get($filename );
    }
    public function showJobImage($filename)
    {
        $decofilename=base64_decode($filename);
        
        $content=Storage::disk('minioout')->get($decofilename);
        

        
        if($content) {
           //get content of image
           $content = Storage::disk('minioout')->get($decofilename);
           //get mime type of image
           $mime = Storage::disk('minioout')->getDriver()->getMimetype($decofilename);      //prepare response with image content and response code
           //dd($mime);
           $headers =  [
            'Content-Type' => $mime,
          ];

           //$response->header("Content-Type", $mime);      // return response
           return  \Response::make(Storage::disk('minioout')->get($decofilename), 200, $headers);
        } else {
           abort(404);
        }

    }
}
