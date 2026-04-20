<?php

declare(strict_types=1);

namespace App\Provider;

interface ProviderClientInterface
{
    public function createMeeting(array $payload): array;
    public function updateMeeting(string $providerMeetingId, array $payload): array;
    public function deleteMeeting(string $providerMeetingId): bool;
}
