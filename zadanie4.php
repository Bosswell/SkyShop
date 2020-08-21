<?php

class TaxRule
{
    private int $tax;
    private string $name;

    public function __construct(string $name, int $tax)
    {
        $this->name = $name;
        $this->tax = $tax;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTax(): int
    {
        return $this->tax;
    }
}

class Product
{
    private string $name;

    /** @var float without tax */
    private float $price;

    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}

class Position
{
    private Product $product;
    private TaxRule $taxRule;
    private int $quantity;

    private float $priceWithoutTax;
    private float $taxAmount;

    public function __construct(Product $product, TaxRule $taxRule, int $quantity)
    {
        $this->product = $product;
        $this->taxRule = $taxRule;
        $this->quantity = $quantity;

        $this->calculatePrice();
    }

    public function getTaxRule(): TaxRule
    {
        return $this->taxRule;
    }

    public function getPriceWithTax(): float
    {
        return $this->priceWithoutTax + $this->taxAmount;
    }

    public function getPriceWithoutTax(): float
    {
        return $this->priceWithoutTax;
    }

    public function getTaxAmount()
    {
        return $this->taxAmount;
    }

    private function calculatePrice()
    {
        $price = $this->product->getPrice();

        if ($this->taxRule->getTax() === 0) {
            $this->taxAmount = 0;
        } else {
            $this->taxAmount = (float)bcmul($this->taxRule->getTax() / 100, $price, 2);
        }

        $this->priceWithoutTax = (float)bcmul($price, $this->quantity, 2);
    }
}

class OrderTaxSummary
{
    private string $name;
    private float $priceWithTax;
    private float $priceWithoutTax;
    private float $taxAmount;

    public function __construct(string $name, float $priceWithTax, float $priceWithoutTax, float $taxAmount)
    {
        $this->name = $name;
        $this->priceWithTax = $priceWithTax;
        $this->priceWithoutTax = $priceWithoutTax;
        $this->taxAmount = $taxAmount;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPriceWithTax(): float
    {
        return $this->priceWithTax;
    }

    public function getTaxAmount(): float
    {
        return $this->taxAmount;
    }
}

class Order
{
    /** @var Position[] */
    private array $positions = [];

    private array $summary = [];
    private float $totalPriceWithoutTax = 0;
    private float $totalPriceWithTax = 0;
    private float $totalTaxAmount = 0;

    public function addPosition(Position $position): void
    {
        $this->positions[] = $position;

        $key = $position->getTaxRule()->getName();
        if (!key_exists($key, $this->summary)) {
            $this->summary[$key]['priceWithTax'] = 0;
            $this->summary[$key]['priceWithoutTax'] = 0;
            $this->summary[$key]['taxAmount'] = 0;
        }

        $this->summary[$key]['priceWithTax'] = bcadd($position->getPriceWithTax(), $this->summary[$key]['priceWithTax'], 2);
        $this->summary[$key]['priceWithoutTax'] = bcadd($position->getPriceWithoutTax(), $this->summary[$key]['priceWithoutTax'], 2);
        $this->summary[$key]['taxAmount'] = bcadd($position->getTaxAmount(), $this->summary[$key]['taxAmount'], 2);

        $this->totalPriceWithTax = bcadd($this->totalPriceWithTax, $this->summary[$key]['priceWithTax'], 2);
        $this->totalPriceWithoutTax = bcadd($this->totalPriceWithoutTax, $this->summary[$key]['priceWithoutTax'], 2);
        $this->totalTaxAmount = bcadd($this->totalTaxAmount, $this->summary[$key]['taxAmount'], 2);
    }

    public function getSummary(): array
    {
        return $this->summary;
    }

    public function getTotalPriceWithoutTax(): float
    {
        return $this->totalPriceWithoutTax;
    }

    public function getTotalPriceWithTax(): float
    {
        return $this->totalPriceWithTax;
    }

    public function getTotalTaxAmount(): float
    {
        return $this->totalTaxAmount;
    }
}

$taxRule1 = new TaxRule('23%', 23);
$taxRule2 = new TaxRule('8%', 8);
$taxRule3 = new TaxRule('npo', 0);

$product1 = new Product('Product 1', 100.33);
$product2 = new Product('Product 2', 22);
$product3 = new Product('Product 3', 23.11);
$product4 = new Product('Product 4', 511.1);

$position1 = new Position($product1, $taxRule1, 3);
$position2 = new Position($product2, $taxRule2, 1);
$position3 = new Position($product3, $taxRule1, 4);
$position4 = new Position($product4, $taxRule3, 1);

$order = new Order();
$order->addPosition($position1);
$order->addPosition($position2);
$order->addPosition($position3);
$order->addPosition($position4);


echo sprintf(
    'Order total price without tax: %s <br/> Order total tax amount: %s <br/> Order total price with tax: %s <br/>',
    $order->getTotalPriceWithoutTax(),
    $order->getTotalTaxAmount(),
    $order->getTotalPriceWithTax()
);

echo '<pre>';
print_r($order->getSummary());
echo '</pre>';