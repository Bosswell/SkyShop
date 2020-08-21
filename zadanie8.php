<?php

class TestClass
{
    public int $var;

    public function __construct(int $var)
    {
        $this->var = $var;
    }
}

$var1 = [1, 2, 3, 4, 5];
$var2 = 8;
$var3 = 1.2;
$var4 = "Hello";

$var5 = new class() {
    public array $arr = [1, 3, 5];
    /** @var TestClass[] */
    public array $objArr = [];

    public function __construct()
    {
        $this->objArr[] = new TestClass(1);
        $this->objArr[] = new TestClass(2);
    }
};

echo '<pre>';
var_dump((array)$var1, (array)$var2, (array)$var3, (array)$var4, (array)$var5);
echo '</pre>';