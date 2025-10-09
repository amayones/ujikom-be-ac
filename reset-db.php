<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Resetting database...\n";

try {
    // Fresh migration
    Artisan::call('migrate:fresh');
    echo "✅ Migration fresh completed\n";
    
    // Seed database
    Artisan::call('db:seed');
    echo "✅ Database seeded successfully\n";
    
    echo "\n🎉 Database reset complete!\n";
    echo "Test credentials:\n";
    echo "- Email: test@test.com\n";
    echo "- Password: test123\n";
    echo "- Role: customer\n\n";
    echo "Admin credentials:\n";
    echo "- Email: admin@cinema.com\n";
    echo "- Password: Admin@2024!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}