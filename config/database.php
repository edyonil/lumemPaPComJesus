<?php

return [

    'default' => 'mongodb',

    'connections' => [

        'mongodb' => array(
            'driver'   => 'mongodb',
            'host'     => 'localhost',
            'port'     => 27017,
            'username' => 'root',
            'password' => '',
            'database' => 'papcj'
        ),

    ],

    'migrations' => 'migrations',
];