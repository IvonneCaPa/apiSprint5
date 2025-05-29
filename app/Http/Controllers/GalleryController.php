<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use Illuminate\Http\Request;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use App\Http\Requests\GalleryRequest;

class GalleryController extends Controller
{
    public function index()
    {
        return response([
            'galleries'=> new GalleryResource(Gallery::all())
        ]);
    }

    public function show(Gallery $gallery)
    {
        return response([
            'gallery'=> new GalleryResource($gallery)
        ]);
    }

    public function store(GalleryRequest $request)
    {
        try {
            $gallery = Gallery::create($request->all());
            return response([
                'gallery'=> new GalleryResource($gallery),
                'message' => 'Galeria creada correctamente'
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'error'=>$th->getMessage()
            ], 500);
        }
    }

    public function update(GalleryRequest $request, Gallery $gallery) {
        try {
            $gallery->update($request->all());
            return response([
                'gallery'=> new GalleryResource($gallery),
                'message' => 'Galería actualizada correctamente'
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'error'=>$th->getMessage()
            ], 500);
        }
    }
}
