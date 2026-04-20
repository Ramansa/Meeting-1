<?php

declare(strict_types=1);

namespace App\Repository;

use wpdb;

final class UserRepository
{
    public function __construct(private wpdb $db)
    {
    }

    public function findByLogin(string $login): ?array
    {
        $sql = $this->db->prepare(
            'SELECT u.ID, u.user_login, u.user_pass, u.display_name, aur.role AS app_role
             FROM wb_users u
             LEFT JOIN app_user_roles aur ON aur.user_id = u.ID
             WHERE u.user_login = %s LIMIT 1',
            $login
        );

        $row = $this->db->get_row($sql, ARRAY_A);
        return $row ?: null;
    }

    public function findByRole(string $role): array
    {
        $sql = $this->db->prepare(
            'SELECT u.ID, u.display_name FROM wb_users u
             INNER JOIN app_user_roles aur ON aur.user_id = u.ID
             WHERE aur.role = %s ORDER BY u.display_name',
            $role
        );

        return $this->db->get_results($sql, ARRAY_A) ?: [];
    }

    public function groupsForUser(int $userId): array
    {
        $sql = $this->db->prepare(
            'SELECT g.id, g.name, mug.group_type
             FROM wb_bp_groups g
             INNER JOIN meeting_user_groups mug ON mug.group_id = g.id
             WHERE mug.user_id = %d',
            $userId
        );

        return $this->db->get_results($sql, ARRAY_A) ?: [];
    }
}
