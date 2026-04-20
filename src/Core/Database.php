<?php

declare(strict_types=1);

namespace App\Core;

use wpdb;

final class Database
{
    private wpdb $db;

    public function __construct()
    {
        if (!class_exists('wpdb')) {
            require_once dirname(__DIR__, 3) . '/wp-includes/wp-db.php';
        }

        $this->db = new wpdb(
            Env::get('WP_DB_USER', ''),
            Env::get('WP_DB_PASSWORD', ''),
            Env::get('WP_DB_NAME', ''),
            Env::get('WP_DB_HOST', '127.0.0.1')
        );

        $this->db->prefix = Env::get('WP_DB_PREFIX', 'wb_');
        $this->db->show_errors(false);
    }

    public function wpdb(): wpdb
    {
        return $this->db;
    }
}
