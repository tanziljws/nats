#!/bin/bash
# Railway deployment setup script

# Create storage symlink if it doesn't exist
if [ ! -L "public/storage" ]; then
    php artisan storage:link
fi

# Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "Storage setup completed!"

