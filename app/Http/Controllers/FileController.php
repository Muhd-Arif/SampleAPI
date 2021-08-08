<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    
    public function indexLocal()
    {
        $files = Storage::allFiles('public/files');
        return response(['files' => $files]);
    }

    public function indexDb()
    {
        return(File::all());

        // $files = collect(File::all())->map(function($file){
        //     return $newArray[] = [$file->name,  $file->file];
        // });

        // return response([
        //     'data' => $files,
        // ]);
    }


    public function uploadLocal(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|max:1999'
        ]);

        // Handle File Upload
        if ($request->hasFile('file')) {
            // Get filename with the extension
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('file')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            // Upload file
            $path = $request->file('file')->storeAs('public/files', $fileNameToStore);

            $response = [
                'file' => $fileNameToStore
            ];
        } else {
            $response = [
                'message' => 'No file choose'
            ];
        }

        return($response);
    }

    public function uploadDb(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'file' => 'required'
        ]);

        $extension = $request->file('file')->getClientOriginalExtension();
        $path = $request->file('file')->getRealPath();
        $data = file_get_contents($path);
        $base64 = base64_encode($data);
        // $base64 = base64_encode(file_get_contents($request->file('file')->pat‌​h()));

        
        $file = File::create([
            'name' => $request->name,
            'file' => $base64,
            'extension' => $extension
        ]);

        if ($file->save()) {
            return $file;
        } else {
            return [
                'message' => 'Failed to upload',
            ];
        }
    }
   
    public function showLocal($filename)
    {
        // return base64_encode(Storage::get('public/files/'.$filename)) ;
        $extension = pathinfo(storage_path('public/files/'.$filename), PATHINFO_EXTENSION);
        $contentType = ($extension == 'jpg') ? 'image/jpg' : (($extension == 'png') ? 'image/png' : 'application/pdf');
        if($extension == 'pdf'){
            $response = response(Storage::get('public/files/'.$filename))->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
            // return response()->download(storage_path('public/files/'.$filename));
        } else
        {
            $response = response(Storage::get('public/files/'.$filename))->header('Content-Type', $contentType);
        }
        return $response;    
    }

    public function showDb($id)
    {
        $file = File::findOrFail($id);

        $image = $file->file;
        $extension = $file->extension;
        $base64 = base64_decode($image);

        $contentType = ($extension == 'jpg') ? 'image/jpg' : (($extension == 'png') ? 'image/png' : 'application/pdf');
        $response = response($base64)->header('Content-Type', $contentType);
        if($extension == 'pdf'){
            $response = response($base64)->header('Content-Disposition', 'attachment');
            // return response()->download(storage_path('public/files/'.$filename));
        }
        return $response;
    }

    
    public function updateDb(Request $request, $id)
    {
        $file = File::findOrFail($id);
        
        if($request->hasFile('file')){
            $extension = $request->file('file')->getClientOriginalExtension();
            $path = $request->file('file')->getRealPath();
            $data = file_get_contents($path);
            $base64 = base64_encode($data);
            $file->file = $base64;
            $file->extension = $extension;
        }
        $file->name = $request->name;
        $file->save();

        return $file;
    }

    
    public function destroyLocal($filename)
    {
        return Storage::delete('public/files/' . $filename);
    }

    public function destroyDb($id)
    {
        return File::destroy($id);
    }
}