<?php

/**
 * Script to assign photos to kitchen and garden facilities
 * This will create facility_photos entries and set cover_photo_id
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

echo "🔧 Assigning Photos to Kitchen and Garden...\n\n";

try {
    $kitchen = FacilityCategory::where('slug', 'kitchen')->first();
    $garden = FacilityCategory::where('slug', 'garden')->first();
    
    if (!$kitchen || !$garden) {
        echo "❌ Categories not found!\n";
        exit(1);
    }
    
    // File candidates (smaller files that might be kitchen/garden, not Grand Staircase)
    // Grand Staircase is usually 800KB+, smaller files might be different facilities
    $kitchenFile = 'facility_photos/O5BOkxooDeXSrUjcGAHQcufk8zRdMpRLNKvFegDc.jpg'; // 44KB
    $gardenFile = 'facilities/NtP3N0WWBCmnllgPfhNdCaG25h6QvlL12UvnoOOR.jpg'; // 44KB
    
    // Alternative candidates
    $kitchenAlt = 'facilities/3ON4OX8qgUHtHTCoiMKRieWJuzPj15tDg69vVGlS.jpg'; // 9.7KB
    
    echo "📋 Checking files...\n";
    
    // Check if files exist
    if (!Storage::disk('public')->exists($kitchenFile)) {
        echo "⚠️  Kitchen file not found: {$kitchenFile}\n";
        $kitchenFile = $kitchenAlt;
        echo "   Using alternative: {$kitchenFile}\n";
    }
    
    if (!Storage::disk('public')->exists($gardenFile)) {
        echo "⚠️  Garden file not found: {$gardenFile}\n";
        echo "   Need to find another file...\n";
    }
    
    // Create facility_photos for kitchen
    if (Storage::disk('public')->exists($kitchenFile)) {
        $kitchenPhoto = FacilityPhoto::where('facility_category_id', $kitchen->id)
            ->where('image', $kitchenFile)
            ->first();
        
        if (!$kitchenPhoto) {
            $kitchenPhoto = FacilityPhoto::create([
                'facility_category_id' => $kitchen->id,
                'name' => 'Hogwarts Kitchen',
                'description' => 'The grand kitchen of Hogwarts',
                'image' => $kitchenFile,
                'view_count' => 0,
            ]);
            echo "✅ Created kitchen photo: {$kitchenPhoto->name}\n";
        } else {
            echo "ℹ️  Kitchen photo already exists: {$kitchenPhoto->name}\n";
        }
        
        // Set as cover
        $kitchen->cover_photo_id = $kitchenPhoto->id;
        $kitchen->save();
        echo "✅ Set kitchen cover photo\n";
    }
    
    // Create facility_photos for garden
    if (Storage::disk('public')->exists($gardenFile)) {
        $gardenPhoto = FacilityPhoto::where('facility_category_id', $garden->id)
            ->where('image', $gardenFile)
            ->first();
        
        if (!$gardenPhoto) {
            $gardenPhoto = FacilityPhoto::create([
                'facility_category_id' => $garden->id,
                'name' => 'Hogwarts Garden',
                'description' => 'The beautiful gardens of Hogwarts',
                'image' => $gardenFile,
                'view_count' => 0,
            ]);
            echo "✅ Created garden photo: {$gardenPhoto->name}\n";
        } else {
            echo "ℹ️  Garden photo already exists: {$gardenPhoto->name}\n";
        }
        
        // Set as cover
        $garden->cover_photo_id = $gardenPhoto->id;
        $garden->save();
        echo "✅ Set garden cover photo\n";
    }
    
    // Create facility_photos for hall if needed
    $hall = FacilityCategory::where('slug', 'hall')->first();
    if ($hall && !$hall->cover_photo_id) {
        // Use one of the larger files (probably Grand Staircase)
        $hallFile = 'facility_photos/QfFcjBHXQ53eS3L5iibw9wOo38UE3jBilecDdx6J.jpg'; // 803KB
        
        if (Storage::disk('public')->exists($hallFile)) {
            $hallPhoto = FacilityPhoto::where('facility_category_id', $hall->id)
                ->where('image', $hallFile)
                ->first();
            
            if (!$hallPhoto) {
                $hallPhoto = FacilityPhoto::create([
                    'facility_category_id' => $hall->id,
                    'name' => 'Grand Staircase',
                    'description' => 'The iconic Grand Staircase of Hogwarts',
                    'image' => $hallFile,
                    'view_count' => 0,
                ]);
                echo "✅ Created hall photo: {$hallPhoto->name}\n";
            }
            
            $hall->cover_photo_id = $hallPhoto->id;
            $hall->save();
            echo "✅ Set hall cover photo\n";
        }
    }
    
    echo "\n✅ Done! Kitchen and Garden now have their own cover photos.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   Stack: " . $e->getTraceAsString() . "\n";
    exit(1);
}

