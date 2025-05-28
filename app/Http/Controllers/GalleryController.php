<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use Illuminate\Http\Request;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;

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
}
