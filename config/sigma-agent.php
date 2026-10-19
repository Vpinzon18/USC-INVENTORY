<?php

return [
    // Temporary compatibility for the already-issued shared token. Set this
    // to false after every agent has received its own asset-scoped credential.
    'allow_legacy_wildcard_tokens' => (bool) env('SIGMA_AGENT_ALLOW_LEGACY_TOKENS', true),
];
