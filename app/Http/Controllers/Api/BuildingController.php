<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BuildingResource;
use App\Models\Building;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BuildingController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $buildings = Building::query()
            ->where(['address', 'latitude', 'longitude',])
            ->get();
        
        return BuildingResource::collection($buildings);
    }
}
