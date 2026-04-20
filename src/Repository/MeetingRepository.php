<?php

declare(strict_types=1);

namespace App\Repository;

use wpdb;

final class MeetingRepository
{
    public function __construct(private wpdb $db)
    {
    }

    public function create(array $data): int
    {
        $this->db->insert('app_meetings', $data, [
            '%s','%d','%d','%d','%d','%s','%s','%s','%s','%s','%s','%d','%d','%s','%s'
        ]);

        return (int) $this->db->insert_id;
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->db->update('app_meetings', $data, ['id' => $id]);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->db->delete('app_meetings', ['id' => $id], ['%d']);
    }

    public function find(int $id): ?array
    {
        $sql = $this->db->prepare('SELECT * FROM app_meetings WHERE id = %d', $id);
        $row = $this->db->get_row($sql, ARRAY_A);
        return $row ?: null;
    }

    public function forCalendar(int $userId, string $role): array
    {
        if ($role === 'admin') {
            return $this->db->get_results('SELECT * FROM app_meetings ORDER BY start_at', ARRAY_A) ?: [];
        }

        $sql = $this->db->prepare(
            'SELECT m.*
             FROM app_meetings m
             LEFT JOIN meeting_participants mp ON mp.meeting_id = m.id
             LEFT JOIN meeting_user_groups mug ON mug.user_id = %d
             WHERE (m.tutor_id = %d OR mp.user_id = %d)
                OR (m.school_group_id = mug.group_id OR m.class_group_id = mug.group_id)
             GROUP BY m.id
             ORDER BY m.start_at',
            $userId,
            $userId,
            $userId
        );

        return $this->db->get_results($sql, ARRAY_A) ?: [];
    }
}
