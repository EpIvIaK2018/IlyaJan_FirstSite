<?php
namespace App;
class User{
    private string $name;
    private string $password;
    private string $email;
    private string $ip;

    /**
     * @param string $name
     * @param string $password
     * @param string $email
     */
    public function __construct(string $name, string $password, string $email, string $ip)
    {
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

}
