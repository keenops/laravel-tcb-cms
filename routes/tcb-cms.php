<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Keenops\LaravelTcbCms\Http\Controllers\IpnController;

Route::middleware(config('tcb-cms.ipn.middleware', ['api']))
    ->post(config('tcb-cms.ipn.route', '/tcb-cms/ipn'), IpnController::class)
    ->name('tcb-cms.ipn');
