<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use Illuminate\Support\Facades\DB;

Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return "Database connection is working!";
    } catch (\Exception $e) {
        return "Could not connect to the database.  Please check your configuration. error:" . $e;
    }
});

Route::get('/haziq', function () {
    try {
        DB::connection()->getPdo();
        return "WTJINGGG JADI DOHHHH";
    } catch (\Exception $e) {
        return "syibal error" . $e;
    }
});

use Illuminate\Support\Facades\Artisan;

Route::get('/run-migration', function () {
    // This command triggers the migration programmatically
    Artisan::call('migrate', ['--force' => true]);
    
    return "Migration completed! " . Artisan::output();
});
