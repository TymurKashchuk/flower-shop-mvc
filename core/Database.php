<?php

namespace core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $cfg = require ROOT_PATH . '/config/database.php';
            $dsn = "mysql:host={$cfg['host']};dbname={$cfg['dbname']};charset={$cfg['charset']}";

            try {
                self::$instance = new PDO($dsn, $cfg['username'], $cfg['password'],[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            }catch (PDOException $e) {
                error_log('[DB Error] ' . $e->getMessage() . PHP_EOL,3,LOG_PATH . '/error.log');
                die('Помилка підключення до бази даних. Спробуйте пізніше.');
            }
        }
        return self::$instance;
    }
    private function __construct(){}
    private function __clone(){}
}