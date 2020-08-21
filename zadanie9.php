<?php

class Singleton
{
    private static ?Singleton $instance = null;
    private string $testString;

    public static function getInstance(): Singleton
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getString(): string
    {
        return $this->testString;
    }

    public function setString(string $str): void
    {
        $this->testString = $str;
    }

    private function __construct()
    {
        $this->testString = "Hello world";
    }

    private function __clone() {}
}


$singleton1 = Singleton::getInstance();
echo $singleton1->getString() . '<br/>';

$singleton1->setString('ęśąćż');
echo $singleton1->getString() . '<br/>';

$singleton2 = Singleton::getInstance();

if ($singleton1 === $singleton2) {
    echo 'Yay! We are the same. <br/>';
}