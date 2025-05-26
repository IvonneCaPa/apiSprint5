<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        return response([
            'activities'=> new ActivityResource(Activity::all())
        ]);
    }

        public function show(Activity $activity)
    {
        return response([
            'activity' => new ActivityResource($activity)
        ]);
    }
}