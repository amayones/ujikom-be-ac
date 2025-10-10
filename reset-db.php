<?php
// Simple database reset script for production
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

try {
    echo "Resetting database...\n";
    
    // Run migrations
    $kernel->call('migrate:fresh', ['--force' => true]);
    echo "Migrations completed.\n";
    
    // Run seeders
    $kernel->call('db:seed', ['--force' => true]);
    echo "Seeding completed.\n";
    
    echo "Database reset successful!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}