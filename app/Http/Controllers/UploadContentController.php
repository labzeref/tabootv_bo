<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChannelResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\VideoResource;
use App\Jobs\ProcessVideoMediaJob;
use App\Models\Channel;
use App\Models\Country;
use App\Models\TempMedia;
use App\Models\Video;
use Illuminate\Http\Request;

class UploadContentController extends Controller
{
    public function videos()
    {
        $videos = VideoResource::collection(Video::orderBy('id', 'desc')->where('user_id',auth()->id())->where('short',false)->paginate(50))->resource;
        $short = false;
        return inertia('content/Index',compact('videos','short'));
    }
    public function shorts()
    {
        $videos = VideoResource::collection(Video::orderBy('id', 'desc')->where('user_id',auth()->id())->where('short',true)->paginate(50))->resource;
        $short = true;
        return inertia('content/Index',compact('videos','short'));
    }
    public function videoShow(Video $video)
    {
        $video = new VideoResource($video->load(['channel', 'country']));

        return inertia('content/show', compact(['video']));
    }

    public function videoCreate()
    {
        $countries = CountryResource::collection(Country::all());
        $creators = ChannelResource::collection(Channel::all());
        $short = false;
        return inertia('content/create',compact('countries','creators','short'));
    }
    public function shortVideoCreate()
    {
        $countries = CountryResource::collection(Country::all());
        $creators = ChannelResource::collection(Channel::all());
        $short = true;
        return inertia('content/create',compact('countries','creators','short'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thumbnail' => 'required|mimetypes:image/*',
            'video' => 'required|string|exists:temp_media,uuid',
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'creator' => 'required|exists:users,id',
            'country' => 'required|exists:countries,id',
            'duration' => 'required',
            'short' => 'required|bool',
        ]);

        $video = Video::create([
            'user_id' => $request->creator,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'country_id' => $request->country,
            'duration' => $request->duration,
            'short' => $request->short,
            'processing' => true,
        ]);

        $tempMedia = TempMedia::where('uuid', $request->video)->firstOrFail();
        $videoPath = $tempMedia->getFirstMediaPath('file');
        $thumbnailPath = $request->file('thumbnail')->store("tmp/videos/$video->id", 'public');
        $thumbnailPath = storage_path('/app/public/' . $thumbnailPath);

        dispatch(new ProcessVideoMediaJob($video, $thumbnailPath, $videoPath));

        return back();
    }

    public function update(Request $request, Video $video)
    {

        if  ($video->user_id != $request->user()->id) {

            return back()->with('error', 'Seems like video does not belongs to you');
        }
        if  ($video->published_at) {
            return back()->with('error', "You can only edit unpublished videos");
        }
        if ($video->processing) {
            return back()->with('error', 'Video is processing, cannot update');
        }

        $request->validate([
            'thumbnail' => 'nullable|mimetypes:image/*',
            'video' => 'nullable|string|exists:temp_media,uuid',
            'title' => 'required',
            'description' => 'required',
            'creator' => 'required|exists:users,id',
            'country' => 'required|exists:countries,id',
            'duration' => 'required',
        ]);

        $video->update([
            'user_id' => $request->creator,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'country_id' => $request->country,
            'duration' => $request->duration,
            'processing' => true,
        ]);

        $videoPath = null;
        $thumbnailPath = null;

        if ($request->video) {
            $tempMedia = TempMedia::where('uuid', $request->video)->firstOrFail();
            $videoPath = $tempMedia->getFirstMediaPath('file');
        }

        if ($request->file('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store("tmp/videos/$video->id", 'public');
            $thumbnailPath = storage_path('/app/public/' . $thumbnailPath);
        }

        dispatch(new ProcessVideoMediaJob($video, $thumbnailPath, $videoPath));

        return to_route('contents.videos.show', $video->id)->with('success', 'Video updated successfully');
    }

    public function videoEdit(Video $video)
    {
        $countries = CountryResource::collection(Country::all());
        $creators = ChannelResource::collection(Channel::all());
        return inertia('content/edit',compact('countries','creators','video'));
    }
    public function shortVideoEdit(Video $video)
    {
        $countries = CountryResource::collection(Country::all());
        $creators = ChannelResource::collection(Channel::all());
        return inertia('content/edit',compact('countries','creators','video'));
    }
    public function destroy(Video $video)
    {
        if  ($video->user_id != auth()->id()) {

            return back()->with('error', 'Seems like video does not belongs to you');
        }
        $video->delete();

        return back();
    }


}
