<?php

/**
 * Script to fix facility category cover photos for kitchen and garden
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\FacilityCategory;
use App\Models\FacilityPhoto;
use Illuminate\Support\Facades\Storage;

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 Fixing Facility Category Cover Photos...\n\n";

try {
    // Get facility categories that need photos
    $hall = FacilityCategory::where('slug', 'hall')->first();
    $kitchen = FacilityCategory::where('slug', 'kitchen')->first();
    $garden = FacilityCategory::where('slug', 'garden')->first();
    
    if (!$hall || !$kitchen || !$garden) {
        echo "❌ Categories not found!\n";
        exit(1);
    }
    
    echo "📋 Found Categories:\n";
    echo "   Hall: ID {$hall->id} (cover_photo_id: " . ($hall->cover_photo_id ?? 'NULL') . ")\n";
    echo "   Kitchen: ID {$kitchen->id} (cover_photo_id: " . ($kitchen->cover_photo_id ?? 'NULL') . ")\n";
    echo "   Garden: ID {$garden->id} (cover_photo_id: " . ($garden->cover_photo_id ?? 'NULL') . ")\n\n";
    
    // Check available photos in storage
    $storagePath = storage_path('app/public');
    $facilityPhotosPath = $storagePath . '/facility_photos';
    $facilitiesPath = $storagePath . '/facilities';
    
    // Find photos that might be suitable for kitchen and garden
    echo "🔍 Looking for suitable photos...\n\n";
    
    // Check for kitchen photo (might be in facilities/ or facility_photos/)
    // Kitchen usually shows cooking areas, hearth, etc.
    $kitchenPhotos = glob($facilityPhotosPath . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
    $facilityPhotos = glob($facilitiesPath . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
    
    // Look for files that might be kitchen or garden related
    // Since we don't know which files are which, we'll check files that aren't used yet
    $usedPhotos = DB::table('facility_photos')->pluck('image')->toArray();
    
    echo "📁 Unused photos in storage:\n";
    $unusedPhotos = [];
    
    foreach ($kitchenPhotos as $file) {
        $relPath = 'facility_photos/' . basename($file);
        if (!in_array($relPath, $usedPhotos)) {
            $unusedPhotos[] = $relPath;
            echo "   - {$relPath}\n";
        }
    }
    
    foreach ($facilityPhotos as $file) {
        $relPath = 'facilities/' . basename($file);
        if (!in_array($relPath, $usedPhotos)) {
            $unusedPhotos[] = $relPath;
            echo "   - {$relPath}\n";
        }
    }
    
    if (empty($unusedPhotos)) {
        echo "   (No unused photos found)\n";
    }
    
    echo "\n";
    
    // Strategy: Use files that look different from Grand Staircase
    // Grand Staircase images are usually large (800KB+), so we'll look for different sized ones
    
    echo "💡 Strategy:\n";
    echo "   - Kitchen and Garden should use different photos\n";
    echo "   - Check existing photos to see which might be suitable\n\n";
    
    // Check what photo Hall is currently showing (if any)
    if ($hall->cover_photo_id) {
        $hallCover = FacilityPhoto::find($hall->cover_photo_id);
        if ($hallCover) {
            echo "ℹ️  Hall currently using: {$hallCover->name} ({$hallCover->image})\n";
        }
    } else {
        echo "ℹ️  Hall has no cover photo (using placeholder)\n";
    }
    
    // Suggest: Create photos for kitchen and garden if files exist
    // We'll need to identify suitable files by checking their content or size
    
    echo "\n🔧 To fix this:\n";
    echo "   1. Upload specific photos for Kitchen and Garden via admin panel\n";
    echo "   2. Or manually create facility_photos entries if files exist\n";
    echo "   3. Set cover_photo_id for each category\n\n";
    
    // Check if we can find suitable photos by checking existing photos that aren't used as covers
    $allPhotos = FacilityPhoto::all();
    
    if ($allPhotos->count() > 0) {
        echo "📷 Existing Facility Photos (not used as covers yet):\n";
        $unusedAsCover = [];
        
        foreach ($allPhotos as $photo) {
            $usedAsCover = FacilityCategory::where('cover_photo_id', $photo->id)->exists();
            if (!$usedAsCover) {
                $unusedAsCover[] = $photo;
                $categoryName = $photo->category ? $photo->category->name : 'N/A';
                echo "   - ID: {$photo->id} | Name: {$photo->name} | Category: {$categoryName} | Image: {$photo->image}\n";
            }
        }
        
        if (empty($unusedAsCover)) {
            echo "   (All photos are already used as covers)\n";
        } else {
            echo "\n💡 You can use these photos as covers for kitchen/garden if appropriate\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

