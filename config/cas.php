<?php

return [
    'host' => env('CAS_HOST', 'cas.eeyes.net'),
    'port' => (int)env('CAS_PORT', 443),
    'context' => env('CAS_CONTEXT', ''),
];
