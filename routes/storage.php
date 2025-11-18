<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/**
 * Fallback route to serve storage files if symlink doesn't work
 * This is a temporary solution until storage:link is properly configured
 */
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    
    if (!file_exists($filePath)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($filePath);
    
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
    ]);
})->where('path', '.*')->name('storage.serve');

