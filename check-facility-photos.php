<?php

/**
 * Script to check facility categories and their cover photos
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Checking Facility Categories and Cover Photos...\n\n";

try {
    // Get all facility categories
    $categories = DB::table('facility_categories')
        ->leftJoin('facility_photos', 'facility_categories.cover_photo_id', '=', 'facility_photos.id')
        ->select(
            'facility_categories.id',
            'facility_categories.name',
            'facility_categories.slug',
            'facility_categories.cover_photo_id',
            'facility_photos.image as cover_image',
            'facility_photos.name as cover_name'
        )
        ->orderBy('facility_categories.sort_order')
        ->get();
    
    echo "📋 Facility Categories:\n";
    echo str_repeat("=", 80) . "\n";
    
    foreach ($categories as $cat) {
        echo "ID: {$cat->id} | Name: {$cat->name} | Slug: {$cat->slug}\n";
        echo "  Cover Photo ID: " . ($cat->cover_photo_id ?? 'NULL') . "\n";
        echo "  Cover Image: " . ($cat->cover_image ?? 'NULL') . "\n";
        echo "  Cover Name: " . ($cat->cover_name ?? 'NULL') . "\n";
        echo "\n";
    }
    
    // Check photos for each category
    echo "\n📷 Photos per Category:\n";
    echo str_repeat("=", 80) . "\n";
    
    foreach ($categories as $cat) {
        $photos = DB::table('facility_photos')
            ->where('facility_category_id', $cat->id)
            ->select('id', 'name', 'image')
            ->get();
        
        echo "Category: {$cat->name} ({$cat->slug})\n";
        echo "  Total Photos: " . $photos->count() . "\n";
        
        foreach ($photos as $photo) {
            $isCover = $photo->id == $cat->cover_photo_id ? " [COVER]" : "";
            echo "    - ID: {$photo->id} | Name: {$photo->name} | Image: {$photo->image}{$isCover}\n";
        }
        echo "\n";
    }
    
    // Check for duplicates
    echo "\n🔍 Checking for Duplicate Cover Images:\n";
    echo str_repeat("=", 80) . "\n";
    
    $coverImages = [];
    foreach ($categories as $cat) {
        if ($cat->cover_image) {
            $imagePath = basename($cat->cover_image);
            if (!isset($coverImages[$imagePath])) {
                $coverImages[$imagePath] = [];
            }
            $coverImages[$imagePath][] = $cat->name;
        }
    }
    
    foreach ($coverImages as $image => $categoryNames) {
        if (count($categoryNames) > 1) {
            echo "⚠️  Image '{$image}' is used by: " . implode(', ', $categoryNames) . "\n";
        }
    }
    
    echo "\n";
    
    // List available photos in storage
    echo "📁 Available Photos in Storage:\n";
    echo str_repeat("=", 80) . "\n";
    
    $storagePath = storage_path('app/public');
    $facilityPhotosPath = $storagePath . '/facility_photos';
    
    if (is_dir($facilityPhotosPath)) {
        $files = glob($facilityPhotosPath . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
        echo "Found " . count($files) . " files in facility_photos/\n";
        
        foreach ($files as $file) {
            $filename = basename($file);
            $size = round(filesize($file) / 1024, 2);
            echo "  - {$filename} ({$size} KB)\n";
        }
    }
    
    $facilitiesPath = $storagePath . '/facilities';
    if (is_dir($facilitiesPath)) {
        $files = glob($facilitiesPath . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
        echo "\nFound " . count($files) . " files in facilities/\n";
        
        foreach ($files as $file) {
            $filename = basename($file);
            $size = round(filesize($file) / 1024, 2);
            echo "  - {$filename} ({$size} KB)\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

