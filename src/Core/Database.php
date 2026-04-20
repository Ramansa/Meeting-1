<?php

declare(strict_types=1);

namespace App\Core;

use wpdb;

final class Database
{
    private wpdb $db;
    private array $envData;

    public function __construct(array $envData)
    {
        $this->envData = $envData;

        if (!class_exists('wpdb')) {
            require_once dirname(__DIR__, 3) . '/wp-includes/wp-db.php';
        }

        $this->db = new wpdb(
            env($this->envData, 'WP_DB_USER', ''),
            env($this->envData, 'WP_DB_PASSWORD', ''),
            env($this->envData, 'WP_DB_NAME', ''),
            env($this->envData, 'WP_DB_HOST', '127.0.0.1')
        );

        $this->db->prefix = env($this->envData, 'WP_DB_PREFIX', 'wb_');
        $this->db->show_errors(false);
    }

    public function wpdb(): wpdb
    {
        return $this->db;
    }
}
