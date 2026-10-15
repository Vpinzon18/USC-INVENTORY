<?php

return [
    'version' => env('SIGMA_AGENT_UPDATE_VERSION', ''),
    'package' => env('SIGMA_AGENT_UPDATE_PACKAGE', ''),
    'sha256' => strtolower((string) env('SIGMA_AGENT_UPDATE_SHA256', '')),
    'mandatory' => filter_var(env('SIGMA_AGENT_UPDATE_MANDATORY', false), FILTER_VALIDATE_BOOL),
    'release_notes' => env('SIGMA_AGENT_UPDATE_NOTES', ''),
];
