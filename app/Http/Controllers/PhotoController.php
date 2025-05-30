<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use Illuminate\Http\Request;
use App\Http\Resources\PhotoResource;
use App\Models\Photo;


class PhotoController extends Controller
{
    public function index(){
        return response([
            'photos' => new PhotoResource(photo::with('gallery')->get())
        ]);
    }

    //crear
    public function store(PhotoRequest $request)
    {
        try {
            if($request->hasFile('location')){
                $location = $request->file('location');
                $path = $location->store('photos');
            };

            $photo = Photo::create([
                'title' => $request->title,
                'location' => $path,
                'gallery_id' => $request->gallery->id, 
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
