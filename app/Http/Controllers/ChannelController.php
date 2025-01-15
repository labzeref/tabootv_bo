<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function creatorsProfile($creator_id)
    {

        return inertia('Community/CreatorsProfile',compact('creator_id'));
    }
}
