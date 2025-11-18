<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/**
 * Fallback route to serve storage files if symlink doesn't work
 * This route is registered in bootstrap/app.php but kept here for reference
 * Main route is in routes/web.php for priority
 */
Route::get('/storage/{path}', function ($path) {
    try {
        // Decode URL-encoded path
        $path = urldecode($path);
        
        // Try to get file from public disk
        if (Storage::disk('public')->exists($path)) {
            $filePath = Storage::disk('public')->path($path);
            
            if (file_exists($filePath) && is_file($filePath)) {
                $mimeType = mime_content_type($filePath);
                if (!$mimeType) {
                    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                    $mimeTypes = [
                        'jpg' => 'image/jpeg',
                        'jpeg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                        'svg' => 'image/svg+xml',
                        'webp' => 'image/webp',
                    ];
                    $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
                }
                
                return response()->file($filePath, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=31536000',
                ]);
            }
        }
        
        // Fallback: try direct path
        $filePath = storage_path('app/public/' . $path);
        
        if (file_exists($filePath) && is_file($filePath)) {
            $mimeType = mime_content_type($filePath);
            if (!$mimeType) {
                $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                $mimeTypes = [
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                    'svg' => 'image/svg+xml',
                    'webp' => 'image/webp',
                ];
                $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
            }
            
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        }
        
        abort(404, 'File not found: ' . $path);
    } catch (\Exception $e) {
        abort(404, 'File not found');
    }
})->where('path', '.*')->name('storage.serve');

