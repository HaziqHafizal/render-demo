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


use Illuminate\Support\Facades\Artisan;

Route::get('/fix-and-migrate', function () {
    // 1. Force clear the configuration cache
    Artisan::call('config:clear');
    
    // 2. Debug: Check what Host Laravel sees NOW
    $host = config('database.connections.pgsql.host');
    
    try {
        // 3. Test the connection
        DB::connection()->getPdo();
        
        // 4. If connection works, run migration
        Artisan::call('migrate', ['--force' => true]);
        
        return "✅ SUCCESS! <br>" .
               "Connected to Host: $host <br>" .
               "Migration Status: " . Artisan::output();
               
    } catch (\Exception $e) {
        return "❌ ERROR. <br>" .
               "Laravel is trying to connect to: <b>$host</b> <br>" .
               "Full Error: " . $e->getMessage();
    }
});
