<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatusResource;
use Illuminate\Http\Request;
use App\Models\Status;
use App\Events\StatusCreated;

class StatusesController extends Controller
{
    public function index(){
        return StatusResource::collection(
            Status::latest()->paginate()
        );
    }

    public function store(Request $request){

        $validStatus = $request->validate(['body' => 'required|min:5']);

        $status = $request->user()->statuses()->create($validStatus);

        $statusResource = StatusResource::make($status);

        StatusCreated::dispatch($statusResource);

        return $statusResource;
    }
}
