<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatusResource;
use Illuminate\Http\Request;
use App\Models\Status;

class StatusesController extends Controller
{
    public function index(){
        return StatusResource::collection(
            Status::latest()->paginate()
        );
    }

    public function store(){

        request()->validate(['body' => 'required|min:5']);

        $status = Status::create([
            'user_id' => auth()->id(),
            'body'=>request('body')
        ]);

        return StatusResource::make($status);
    }
}
