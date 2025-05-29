<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PhotoResource;

class PhotoController extends Controller
{
    public function index()
    {
        return response([
            'photos' => new PhotoResource(Photo::with)
        ]);
    }
}
