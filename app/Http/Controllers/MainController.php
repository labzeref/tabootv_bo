<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\PostCommentResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\VideoResource;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Country;
use App\Models\PostComment;
use App\Models\Tag;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home()
    {
        return inertia('Home/Index');
    }

    public function videos()
    {
        $categories = CategoryResource::collection(Category::all());
        $countries = CountryResource::collection(Country::whereHas('videos')->distinct()->get());

        return inertia('Videos/Index', compact(['categories', 'countries']));
    }

    public function searches()
    {
        $categories = CategoryResource::collection(Category::all());
        $countries = CountryResource::collection(Country::whereHas('videos')->distinct()->get());
        return inertia('Search/Index',compact(['categories', 'countries']));
    }

    public function community(Request $request)
    {
        $post_id = $request->route('post'); // Retrieve the 'post' parameter from the route
        return inertia('Community/Index',compact('post_id'));
    }

    public function creator()
    {
        return inertia('Community/Creators');
    }


}
