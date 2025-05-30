<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use Illuminate\Http\Request;
use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index(){
        return response([
            'photos' => PhotoResource::collection(Photo::with('gallery')->get())
        ]);
    }

    //crear
    public function store(PhotoRequest $request)
    {
        try {
            $path = null;
            
            if($request->hasFile('location')){
                $location = $request->file('location');
                $path = $location->store('photos', 'public');
            }

            $photo = Photo::create([
                'title' => $request->title,
                'location' => $path,
                'gallery_id' => $request->gallery_id, 
            ]);

            return response([
                'photo' => new PhotoResource($photo),
                'message' => 'Foto subida satisfactoriamente'
            ]);

        } catch (\Throwable $th) {
            return response([
                'error'=> $th->getMessage()
            ], 500);
        }
    }
}