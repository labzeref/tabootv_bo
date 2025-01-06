<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\VideoResource;
use App\Models\Category;
use App\Models\Country;
use App\Models\Tag;
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
}
