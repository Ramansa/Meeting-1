<?php

declare(strict_types=1);

namespace App\Provider;

final class ZoomClient implements ProviderClientInterface
{
    public function __construct(private string $clientId, private string $clientSecret, private string $accountId)
    {
    }

    public function createMeeting(array $payload): array
    {
        return [
            'provider_meeting_id' => 'zoom_' . bin2hex(random_bytes(6)),
            'join_url' => 'zoommtg://zoom.us/join?action=join&confno=123456789',
            'start_url' => 'zoommtg://zoom.us/start?confno=123456789',
            'raw' => json_encode($payload),
        ];
    }

    public function updateMeeting(string $providerMeetingId, array $payload): array
    {
        return [
            'provider_meeting_id' => $providerMeetingId,
            'join_url' => 'zoommtg://zoom.us/join?action=join&confno=123456789',
            'start_url' => 'zoommtg://zoom.us/start?confno=123456789',
            'raw' => json_encode($payload),
        ];
    }

    public function deleteMeeting(string $providerMeetingId): bool
    {
        return true;
    }
}
