<?php

declare(strict_types=1);

namespace App\Service;

use App\Core\Env;
use App\Provider\ProviderClientInterface;
use App\Provider\TeamsClient;
use App\Provider\ZoomClient;

final class ProviderFactory
{
    public function make(string $provider): ProviderClientInterface
    {
        if ($provider === 'teams') {
            return new TeamsClient(
                Env::get('TEAMS_TENANT_ID', ''),
                Env::get('TEAMS_CLIENT_ID', ''),
                Env::get('TEAMS_CLIENT_SECRET', '')
            );
        }

        return new ZoomClient(
            Env::get('ZOOM_CLIENT_ID', ''),
            Env::get('ZOOM_CLIENT_SECRET', ''),
            Env::get('ZOOM_ACCOUNT_ID', '')
        );
    }
}
