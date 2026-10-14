<?php

declare(strict_types=1);

use App\Http\Controllers\Controller;

arch()->preset()->php();
arch()->preset()->strict()->ignoring([
    Controller::class,
]);
arch()->preset()->laravel();
arch()->preset()->security()->ignoring([
    'assert',
]);

arch('controllers extend the base controller')
    ->expect('App\Http\Controllers')
    ->classes()
    ->toExtend(Controller::class)
    ->ignoring(Controller::class);
