<?php

use App\Actions\Asset\CategoryList;
use App\Actions\Asset\CountryList;
use App\Actions\Asset\GenderList;
use Illuminate\Support\Facades\Route;

Route::get('/countries', CountryList::class)->name('countries');
Route::get('/countries/used', \App\Actions\Asset\CountryUsedList::class)->name('countries.used');
Route::get('/categories', CategoryList::class)->name('categories');
Route::get('/gender', GenderList::class)->name('gender-list');
