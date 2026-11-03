<?php

return [
    // Legacy wildcard tokens grant access beyond an individual asset. Enable
    // only as a temporary migration exception, then revoke those tokens.
    'allow_legacy_wildcard_tokens' => (bool) env('SIGMA_AGENT_ALLOW_LEGACY_TOKENS', false),
];
