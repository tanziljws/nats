<?php

/**
 * Script to sync missing files from production
 * This script will:
 * 1. Connect to production database
 * 2. Find missing files referenced in database
 * 3. Download them from production URL
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$productionUrl = 'https://nats-production-6ef6.up.railway.app';
$storagePath = storage_path('app/public');
$missingFiles = [];

echo "🔍 Checking for missing files from production database...\n\n";

try {
    // Get school profile data
    $profile = DB::table('school_profiles')->first();
    
    if (!$profile) {
        echo "❌ No school profile found in database.\n";
        exit(1);
    }
    
    echo "📋 School Profile Found:\n";
    echo "   Title: " . ($profile->title ?? 'N/A') . "\n";
    echo "   Logo: " . ($profile->logo ?? 'N/A') . "\n";
    echo "   Hero Image: " . ($profile->hero_image ?? 'N/A') . "\n\n";
    
    // Check logo
    if (!empty($profile->logo)) {
        $logoPath = $storagePath . '/' . $profile->logo;
        if (!file_exists($logoPath)) {
            echo "❌ Missing Logo: {$profile->logo}\n";
            $missingFiles[] = [
                'path' => $profile->logo,
                'type' => 'logo',
                'url' => $productionUrl . '/storage/' . $profile->logo
            ];
        } else {
            echo "✅ Logo exists: {$profile->logo}\n";
        }
    }
    
    // Check hero image
    if (!empty($profile->hero_image)) {
        $heroPath = $storagePath . '/' . $profile->hero_image;
        if (!file_exists($heroPath)) {
            echo "❌ Missing Hero Image: {$profile->hero_image}\n";
            $missingFiles[] = [
                'path' => $profile->hero_image,
                'type' => 'hero_image',
                'url' => $productionUrl . '/storage/' . $profile->hero_image
            ];
        } else {
            echo "✅ Hero Image exists: {$profile->hero_image}\n";
        }
    }
    
    // Check other images (founders, houses, etc)
    echo "\n🔍 Checking other images...\n";
    
    // Founders
    $founders = DB::table('founders')->get();
    foreach ($founders as $founder) {
        if (!empty($founder->photo)) {
            $photoPath = $storagePath . '/' . $founder->photo;
            if (!file_exists($photoPath)) {
                echo "❌ Missing Founder Photo: {$founder->photo}\n";
                $missingFiles[] = [
                    'path' => $founder->photo,
                    'type' => 'founder',
                    'url' => $productionUrl . '/storage/' . $founder->photo
                ];
            }
        }
    }
    
    // Houses
    $houses = DB::table('houses')->get();
    foreach ($houses as $house) {
        if (!empty($house->logo)) {
            $logoPath = $storagePath . '/' . $house->logo;
            if (!file_exists($logoPath)) {
                echo "❌ Missing House Logo: {$house->logo}\n";
                $missingFiles[] = [
                    'path' => $house->logo,
                    'type' => 'house',
                    'url' => $productionUrl . '/storage/' . $house->logo
                ];
            }
        }
    }
    
    // Check if files exist in alternative locations (school-logos, etc)
    echo "\n🔍 Checking alternative locations...\n";
    $alternatives = [];
    
    foreach ($missingFiles as $key => $file) {
        $filename = basename($file['path']);
        
        // Check in school-logos folder for logos
        if ($file['type'] === 'logo') {
            $altPath = $storagePath . '/school-logos/' . $filename;
            if (file_exists($altPath)) {
                echo "ℹ️  Found alternative: school-logos/{$filename}\n";
                $alternatives[] = [
                    'original' => $file['path'],
                    'alternative' => 'school-logos/' . $filename,
                    'type' => 'logo'
                ];
                unset($missingFiles[$key]);
            }
        }
        
        // Check in hero_images folder (already checked, but double check)
        if ($file['type'] === 'hero_image') {
            $altPath = $storagePath . '/hero_images/' . $filename;
            if (file_exists($altPath)) {
                echo "ℹ️  Found alternative: hero_images/{$filename}\n";
                $alternatives[] = [
                    'original' => $file['path'],
                    'alternative' => 'hero_images/' . $filename,
                    'type' => 'hero_image'
                ];
                unset($missingFiles[$key]);
            }
        }
    }
    
    $missingFiles = array_values($missingFiles); // Re-index array
    
    echo "\n";
    
    if (empty($missingFiles)) {
        echo "✅ All files are present in local storage!\n";
        if (!empty($alternatives)) {
            echo "\n💡 Note: Some files were found in alternative locations.\n";
            echo "   You may want to copy them to the correct location or update the database.\n";
        }
        exit(0);
    }
    
    echo "📥 Found " . count($missingFiles) . " missing file(s). Attempting to download...\n\n";
    
    // Download missing files
    $downloaded = 0;
    $failed = 0;
    
    foreach ($missingFiles as $file) {
        echo "📥 Downloading: {$file['path']}... ";
        
        try {
            // Create directory if needed
            $fileDir = dirname($storagePath . '/' . $file['path']);
            if (!is_dir($fileDir)) {
                mkdir($fileDir, 0755, true);
            }
            
            // Download file using cURL with proper headers
            $ch = curl_init($file['url']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36');
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            
            $fileContent = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($fileContent === false || $httpCode !== 200) {
                echo "❌ Failed (HTTP {$httpCode})" . ($error ? ": {$error}" : "") . "\n";
                $failed++;
                continue;
            }
            
            // Save file
            $localPath = $storagePath . '/' . $file['path'];
            file_put_contents($localPath, $fileContent);
            
            if (file_exists($localPath)) {
                echo "✅ Success\n";
                $downloaded++;
            } else {
                echo "❌ Failed (save error)\n";
                $failed++;
            }
            
        } catch (Exception $e) {
            echo "❌ Failed: " . $e->getMessage() . "\n";
            $failed++;
        }
    }
    
    echo "\n";
    echo "📊 Summary:\n";
    echo "   ✅ Downloaded: {$downloaded}\n";
    echo "   ❌ Failed: {$failed}\n";
    
    if ($failed > 0) {
        echo "\n⚠️  Files could not be downloaded from production (HTTP 403).\n";
        echo "   This means the files are not publicly accessible or don't exist on production.\n\n";
        echo "💡 Solutions:\n";
        echo "   1. Upload the files via admin panel at: {$productionUrl}/admin/school-profile/edit\n";
        echo "   2. Or copy similar files from school-logos/ to school/ folder\n";
        echo "   3. Or manually download from production storage if you have access\n\n";
        
        // Try to copy from alternative locations
        if (!empty($alternatives)) {
            echo "🔄 Attempting to copy from alternative locations...\n";
            foreach ($alternatives as $alt) {
                $source = $storagePath . '/' . $alt['alternative'];
                $dest = $storagePath . '/' . $alt['original'];
                $destDir = dirname($dest);
                
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (copy($source, $dest)) {
                    echo "   ✅ Copied: {$alt['alternative']} → {$alt['original']}\n";
                    $downloaded++;
                } else {
                    echo "   ❌ Failed to copy: {$alt['alternative']}\n";
                }
            }
            echo "\n";
        }
    }
    
    if ($downloaded > 0) {
        echo "💡 Run 'git add storage/app/public/' and commit to add these files to git.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

