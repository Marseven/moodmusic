<?php

namespace App\Http\Middleware;

use Common\Core\BaseVerifyCsrfToken;

class VerifyCsrfToken extends BaseVerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/v1/billing/ebilling/*' // Désactive CSRF pour les routes API eBilling
    ];
}
