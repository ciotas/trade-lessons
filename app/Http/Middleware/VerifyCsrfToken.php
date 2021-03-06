<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'videos/return/back',
        'vod/decrypt',
        'video/upload',
        'video/refresh/upload',
        'videos/update/videoId'
    ];
}
