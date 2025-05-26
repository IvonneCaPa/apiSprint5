<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use App\Http\Requests\ActivityRequest;
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

    public function store(ActivityRequest $request)
        {
            try {
                $activity = Activity::create($request->all());
                return response([
                    'activity'=> new ActivityResource($activity),
                    'message' => 'Actividad creada correctamente'
                ], 201);
            } catch (\Throwable $th) {
                //throw $th;
                return response([
                    'error'=>$th->getMessage()
                ], 500);
            }
        }
}