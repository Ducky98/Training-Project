<?php

return [

  'default' => 'main',

  'connections' => [

    'main' => [
      'salt' => env('HASHIDS_SALT', 'your-secret-salt-key'), // Change this to a secure random string
      'length' => 10, // Minimum length of the hash (adjust as needed)
    ],

    'alternative' => [
      'salt' => env('HASHIDS_SALT_ALT', 'another-secret-salt'),
      'length' => 12,
    ],

  ],

];
