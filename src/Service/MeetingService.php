<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\LessonRepository;
use App\Repository\MeetingRepository;
use wpdb;

final class MeetingService
{
    public function __construct(
        private MeetingRepository $meetings,
        private LessonRepository $lessons,
        private ProviderFactory $providerFactory,
        private wpdb $db
    ) {
    }

    public function create(array $data): int
    {
        $startAt = new \DateTimeImmutable($data['start_at']);
        $duration = (int) $data['duration_minutes'];
        $endAt = $startAt->modify(sprintf('+%d minutes', $duration));

        if (!$this->checkTutorAvailability((int) $data['tutor_id'], $startAt, $endAt)) {
            throw new \RuntimeException('Tutor unavailable in selected slot.');
        }

        if (!$this->lessons->consumeCredit((int) $data['student_id'])) {
            throw new \RuntimeException('No available lesson credits.');
        }

        $provider = $this->providerFactory->make($data['provider']);
        $remote = $provider->createMeeting($data);

        $meetingId = $this->meetings->create([
            'title' => $data['title'],
            'tutor_id' => (int) $data['tutor_id'],
            'student_id' => (int) $data['student_id'],
            'school_group_id' => (int) $data['school_group_id'],
            'class_group_id' => (int) $data['class_group_id'],
            'provider' => $data['provider'],
            'provider_meeting_id' => $remote['provider_meeting_id'],
            'join_url' => $remote['join_url'],
            'start_url' => $remote['start_url'],
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
            'duration_minutes' => $duration,
            'lesson_id' => (int) ($data['lesson_id'] ?? 0),
            'status' => 'Not Verified',
            'provider_payload' => (string) $remote['raw'],
        ]);

        $this->db->insert('meeting_participants', ['meeting_id' => $meetingId, 'user_id' => (int)$data['student_id'], 'attendance' => 'Not Verified']);

        return $meetingId;
    }

    public function update(int $id, array $data): bool
    {
        $meeting = $this->meetings->find($id);
        if (!$meeting) {
            return false;
        }

        $startAt = new \DateTimeImmutable($data['start_at']);
        $endAt = $startAt->modify(sprintf('+%d minutes', (int)$data['duration_minutes']));

        $provider = $this->providerFactory->make($meeting['provider']);
        $remote = $provider->updateMeeting($meeting['provider_meeting_id'], $data);

        return $this->meetings->update($id, [
            'title' => $data['title'],
            'start_at' => $startAt->format('Y-m-d H:i:s'),
            'end_at' => $endAt->format('Y-m-d H:i:s'),
            'duration_minutes' => (int)$data['duration_minutes'],
            'join_url' => $remote['join_url'],
            'start_url' => $remote['start_url'],
            'provider_payload' => (string) $remote['raw'],
        ]);
    }

    public function delete(int $id): bool
    {
        $meeting = $this->meetings->find($id);
        if (!$meeting) {
            return false;
        }

        $provider = $this->providerFactory->make($meeting['provider']);
        $provider->deleteMeeting($meeting['provider_meeting_id']);

        return $this->meetings->delete($id);
    }

    public function setStatus(int $meetingId, string $status): bool
    {
        $ok = $this->meetings->update($meetingId, ['status' => $status]);
        if (!$ok) {
            return false;
        }

        $meeting = $this->meetings->find($meetingId);
        if (!$meeting) {
            return false;
        }

        if (in_array($status, ['Tutor Absent', 'Student Absent', 'Cancelled'], true)) {
            $this->lessons->restoreCredit((int)$meeting['student_id']);
        }

        return true;
    }

    private function checkTutorAvailability(int $tutorId, \DateTimeImmutable $startAt, \DateTimeImmutable $endAt): bool
    {
        $weekday = (int) $startAt->format('N');
        $timeStart = $startAt->format('H:i:s');
        $timeEnd = $endAt->format('H:i:s');

        $availabilitySql = $this->db->prepare(
            'SELECT id FROM app_tutor_availability
             WHERE tutor_id = %d AND weekday = %d
               AND start_time <= %s AND end_time >= %s
             LIMIT 1',
            $tutorId,
            $weekday,
            $timeStart,
            $timeEnd
        );

        $availability = $this->db->get_row($availabilitySql, ARRAY_A);
        if (!$availability) {
            return false;
        }

        $conflictSql = $this->db->prepare(
            'SELECT id FROM app_meetings
             WHERE tutor_id = %d
               AND start_at < %s
               AND end_at > %s
             LIMIT 1',
            $tutorId,
            $endAt->format('Y-m-d H:i:s'),
            $startAt->format('Y-m-d H:i:s')
        );

        return !$this->db->get_row($conflictSql, ARRAY_A);
    }
}
