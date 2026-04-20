<?php

declare(strict_types=1);

namespace App\Repository;

use wpdb;

final class LessonRepository
{
    public function __construct(private wpdb $db)
    {
    }

    public function assignPackage(int $userId, int $packageId, int $remaining, bool $unlimited): bool
    {
        return (bool) $this->db->insert('app_user_lesson_packages', [
            'user_id' => $userId,
            'package_id' => $packageId,
            'remaining_credits' => $remaining,
            'is_unlimited' => $unlimited ? 1 : 0,
            'created_at' => gmdate('Y-m-d H:i:s'),
        ]);
    }

    public function consumeCredit(int $userId): bool
    {
        $sql = $this->db->prepare(
            'SELECT * FROM app_user_lesson_packages
             WHERE user_id = %d AND (is_unlimited = 1 OR remaining_credits > 0)
             ORDER BY is_unlimited DESC, id ASC LIMIT 1',
            $userId
        );

        $pkg = $this->db->get_row($sql, ARRAY_A);
        if (!$pkg) {
            return false;
        }

        if ((int) $pkg['is_unlimited'] === 1) {
            return true;
        }

        $newCredits = (int) $pkg['remaining_credits'] - 1;
        return (bool) $this->db->update('app_user_lesson_packages', ['remaining_credits' => $newCredits], ['id' => (int)$pkg['id']]);
    }

    public function restoreCredit(int $userId): bool
    {
        $sql = $this->db->prepare(
            'SELECT * FROM app_user_lesson_packages
             WHERE user_id = %d AND is_unlimited = 0
             ORDER BY id DESC LIMIT 1',
            $userId
        );
        $pkg = $this->db->get_row($sql, ARRAY_A);
        if (!$pkg) {
            return false;
        }
        return (bool) $this->db->update('app_user_lesson_packages', ['remaining_credits' => ((int)$pkg['remaining_credits']) + 1], ['id' => (int)$pkg['id']]);
    }
}
