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

    public function update(ActivityRequest $request, Activity $activity)
    {
        try {
            $activity->update($request->all());
            return response([
                'activity'=> new ActivityResource($activity),
                'message' => 'Actividad actualizada correctamente'
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'error'=>$th->getMessage()
            ], 500);
        }
    }    

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return response([
            'message' => 'Actividad eliminada correctamente'
        ], 200);
    }
}