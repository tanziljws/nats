<?php

/**
 * Database Import Script
 * Imports SQL file to Railway MySQL database
 */

$host = 'shuttle.proxy.rlwy.net';
$port = 39921;
$database = 'railway';
$username = 'root';
$password = 'orAlqYApGVUgRHHxvIjdaxROnmNdieNW';
$sqlFile = __DIR__ . '/../galeri-sekolah (2).sql';

try {
    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "Connected to database successfully!\n";
    echo "Reading SQL file: {$sqlFile}\n";

    if (!file_exists($sqlFile)) {
        throw new Exception("SQL file not found: {$sqlFile}");
    }

    $sql = file_get_contents($sqlFile);
    
    // Remove MySQL-specific comments and statements that might cause issues
    $sql = preg_replace('/^--.*$/m', '', $sql);
    $sql = preg_replace('/^\/\*.*?\*\/;?$/ms', '', $sql);
    
    // Split by semicolon to execute statements one by one
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && 
                   !preg_match('/^(SET|START|COMMIT|\/\*)/i', $stmt);
        }
    );

    echo "Executing " . count($statements) . " SQL statements...\n";

    $pdo->exec("SET FOREIGN_KEY_CHECKS=0;");
    
    $executed = 0;
    $errors = 0;
    
    foreach ($statements as $index => $statement) {
        if (empty(trim($statement))) {
            continue;
        }
        
        try {
            $pdo->exec($statement);
            $executed++;
            
            if (($executed % 50) == 0) {
                echo "Progress: {$executed} statements executed...\n";
            }
        } catch (PDOException $e) {
            // Skip errors for existing tables/indexes
            if (strpos($e->getMessage(), 'already exists') === false && 
                strpos($e->getMessage(), 'Duplicate key') === false) {
                $errors++;
                echo "Warning: " . $e->getMessage() . "\n";
            }
        }
    }
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1;");
    
    echo "\nImport completed!\n";
    echo "Successfully executed: {$executed} statements\n";
    if ($errors > 0) {
        echo "Errors encountered: {$errors}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

