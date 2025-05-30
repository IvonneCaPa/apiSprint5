<?php

namespace App\Http\Controllers;

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
    public function store()
    {
        
    }
}
