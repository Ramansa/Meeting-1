<?php

declare(strict_types=1);

namespace App\Repository;

use wpdb;

final class ReportRepository
{
    public function __construct(private wpdb $db)
    {
    }

    public function summary(): array
    {
        $totals = $this->db->get_row('SELECT COUNT(*) total, SUM(status = "Present") present_count FROM app_meetings', ARRAY_A) ?: ['total' => 0, 'present_count' => 0];
        $tutor = $this->db->get_results('SELECT tutor_id, COUNT(*) total_meetings FROM app_meetings GROUP BY tutor_id ORDER BY total_meetings DESC LIMIT 10', ARRAY_A) ?: [];
        return ['totals' => $totals, 'tutor' => $tutor];
    }
}
