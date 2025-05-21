<?php
class Connection {
    public static function getConnection() {
        $host = getenv('DB_HOST') ?: 'db';
        $dbname = getenv('DB_DATABASE') ?: 'banco_digital';
        $user = getenv('DB_USERNAME') ?: 'root';
        $pass = getenv('DB_PASSWORD') ?: 'root';
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

        try {
            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }
}