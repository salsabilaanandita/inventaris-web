<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default hash driver that will be used to hash
    | passwords for your application. By default, the bcrypt algorithm is
    | used; however, you are free to modify this option if you wish.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => env('HASH_DRIVER', 'bcrypt'),

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options for when passwords are
    | hashed using the Bcrypt algorithm. This will allow you to control
    | the amount of time it takes to hash the given credentials.
    |
    */

    'bcrypt' => [
        'rounds' => ((int) env('BCRYPT_ROUNDS') >= 4 && (int) env('BCRYPT_ROUNDS') <= 31) 
            ? (int) env('BCRYPT_ROUNDS') 
            : 12,
        'verify' => env('HASH_VERIFY', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options for when passwords are
    | hashed using the Argon algorithm. These will allow you to control
    | the amount of time and memory it takes to hash the credentials.
    |
    */

    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
        'verify' => env('HASH_VERIFY', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rehash On Login
    |--------------------------------------------------------------------------
    |
    | When this option is enabled, Laravel will automatically rehash passwords
    | that need to be rehashed when users authenticate. This can help to
    | ensure that passwords stay up-to-date with current standards.
    |
    */

    'rehash_on_login' => true,

];
