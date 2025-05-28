<?php

namespace App\Http\Controllers;

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
}
