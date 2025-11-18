<?php

/**
 * Script to update school logo to use the correct file
 */

require __DIR__ . '/vendor/autoload.php';

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SchoolProfile;
use Illuminate\Support\Facades\Storage;

echo "🔄 Updating school logo...\n\n";

try {
    $profile = SchoolProfile::first();
    
    if (!$profile) {
        echo "❌ No school profile found.\n";
        exit(1);
    }
    
    $correctLogoPath = 'school-logos/60bNIiKDb0AUw1RQVu9l5QgubdbPznDOeYj4Ldo0.png';
    $currentLogoPath = $profile->logo;
    
    echo "📋 Current logo: " . ($currentLogoPath ?? 'N/A') . "\n";
    echo "✅ Correct logo: {$correctLogoPath}\n\n";
    
    // Check if correct logo file exists
    if (!Storage::disk('public')->exists($correctLogoPath)) {
        echo "❌ Correct logo file not found: {$correctLogoPath}\n";
        exit(1);
    }
    
    // Copy to school folder if needed (to match current database path)
    $targetPath = 'school/1uCYg8u2BQ7Zgls98dHsijNv49sSd2QW1KF3WBvQ.png';
    
    if (Storage::disk('public')->exists($correctLogoPath)) {
        // Copy file to target path
        $sourceContent = Storage::disk('public')->get($correctLogoPath);
        Storage::disk('public')->put($targetPath, $sourceContent);
        
        echo "✅ Copied logo to: {$targetPath}\n";
        
        // Update database if path is different
        if ($currentLogoPath !== $targetPath) {
            $profile->logo = $targetPath;
            $profile->save();
            echo "✅ Updated database to use: {$targetPath}\n";
        } else {
            echo "ℹ️  Database already points to correct path.\n";
        }
    }
    
    echo "\n✅ Logo update complete!\n";
    echo "   File: {$targetPath}\n";
    echo "   Size: " . number_format(Storage::disk('public')->size($targetPath) / 1024, 2) . " KB\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

