<?php

declare(strict_types=1);

namespace App\Provider;

final class TeamsClient implements ProviderClientInterface
{
    public function __construct(private string $tenantId, private string $clientId, private string $clientSecret)
    {
    }

    public function createMeeting(array $payload): array
    {
        return [
            'provider_meeting_id' => 'teams_' . bin2hex(random_bytes(6)),
            'join_url' => 'msteams://teams.microsoft.com/l/meetup-join/meeting',
            'start_url' => 'msteams://teams.microsoft.com/l/meetup-join/meeting',
            'raw' => json_encode($payload),
        ];
    }

    public function updateMeeting(string $providerMeetingId, array $payload): array
    {
        return [
            'provider_meeting_id' => $providerMeetingId,
            'join_url' => 'msteams://teams.microsoft.com/l/meetup-join/meeting',
            'start_url' => 'msteams://teams.microsoft.com/l/meetup-join/meeting',
            'raw' => json_encode($payload),
        ];
    }

    public function deleteMeeting(string $providerMeetingId): bool
    {
        return true;
    }
}
