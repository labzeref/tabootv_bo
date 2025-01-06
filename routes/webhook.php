<?php

use Illuminate\Support\Facades\Route;

Route::post('/webhook', \App\Http\Controllers\Webhook\WebhookController::class);
Route::post('/webhook/apple', \App\Http\Controllers\Webhook\AppleWebhookController::class);
