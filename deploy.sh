echo "Deploying application ..."
(php artisan down) || true
  git fetch origin main
  git reset --hard origin/main
  composer install --no-interaction --prefer-dist --optimize-autoloader
  php artisan migrate --force
  npm install
  npm run build
  php artisan optimize
php artisan up
echo "Application deployed!"
