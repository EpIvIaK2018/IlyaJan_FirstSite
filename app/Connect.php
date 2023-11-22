<?php
namespace App;
final class Connect{
    private static array $dbInfo;
    private static mixed $host, $dbname, $username, $password;
    private static ?\PDO $link = null;

    private static ?self $instance = null;

    /**
     * @return Connect
     */
    public static function getInstance(): self
    {
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        try {
            self::connect();
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
        self::getLink();
    }

    public static function getLink(): ?\PDO
    {
        return self::$link;
    }

    public static function connect(): void
    {
        self::$dbInfo = require_once 'dbinfo.php';
        [self::$host, self::$dbname, self::$username,self::$password] = self::$dbInfo;
        //print_r(self::$password);
        self::$link = new \PDO('mysql:host=' . self::$host . ';dbname=' . self::$dbname, self::$username, self::$password);
    }

    public function __serialize(): array
    {
        return [
            'user' => $this->username,
            'pass' => $this->password,
        ];
    }

    public function __unserialize(array $data): void
    {
        self::$username = $data['user'];
        self::$password = $data['pass'];
        new \PDO('mysql:host=' . self::$host . ';dbname=' . self::$dbname, self::$username, self::$password);
    }
}

