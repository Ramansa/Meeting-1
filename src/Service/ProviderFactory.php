<?php

declare(strict_types=1);

namespace App\Service;

use App\Provider\ProviderClientInterface;
use App\Provider\TeamsClient;
use App\Provider\ZoomClient;
use function App\Core\env;

final class ProviderFactory
{
    public function __construct(private array $envData)
    {
    }

    public function make(string $provider): ProviderClientInterface
    {
        if ($provider === 'teams') {
            return new TeamsClient(
                env($this->envData, 'TEAMS_TENANT_ID', ''),
                env($this->envData, 'TEAMS_CLIENT_ID', ''),
                env($this->envData, 'TEAMS_CLIENT_SECRET', '')
            );
        }

        return new ZoomClient(
            env($this->envData, 'ZOOM_CLIENT_ID', ''),
            env($this->envData, 'ZOOM_CLIENT_SECRET', ''),
            env($this->envData, 'ZOOM_ACCOUNT_ID', '')
        );
    }
}
