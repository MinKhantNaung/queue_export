
# Laravel Excel Export with Queue Using FastExcel

## Description

This repository contains a Laravel project that demonstrates how to efficiently handle Excel exports using the FastExcel package. The project leverages Laravel's powerful queue system to handle large data exports asynchronously, ensuring that the application remains responsive even during intensive export operations.

## Installation

1. **Clone the repository**:
git clone https://...

cd queue_export

2. **Install dependencies**:
composer install

npm install

npm run dev

3. **Environment setup**:
cp .env.example .env

php artisan key:generate

4. **Migrate the database**:
php artisan migrate

5. **Run the queue worker**:
php artisan queue:work








