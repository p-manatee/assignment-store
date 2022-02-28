<?php
require_once 'interfaces.php';
//Definition of classes for different types of products
class Product
{
    protected $name;
    protected $brand;
    protected $price;

    public function __get($property)
    {

        return $this->$property;
    }
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function __construct($name, $brand, $price)
    {
        $this->name = $name;
        $this->brand = $brand;
        $this->price = $price;
    }
}
//perishable class has extra properties and implements abstract method to calculate discount
class Perishable extends Product implements Discount
{
    protected $expirationDate;
    protected $discountPercentage = null;
    public function calcDiscount($purchaseDate)
    {

        $interval = date_diff($this->expirationDate, $purchaseDate);
        $days =  $interval->format('%R%a');
        if ((int) $days <= 5 && (int) $days > 0) {
            $this->discountPercentage = 30;
        }

        if ((int)$days == 0) {
            $this->discountPercentage = 70;
        }

        return $this->discountPercentage;
    }
}
class Food extends Perishable
{
}
class Beverage extends Perishable
{
}
//clothes class has extra properties and implements abstract method to calculate discount
class Clothes extends Product implements Discount
{
    protected $size;
    protected $color;
    protected $discountPercentage = null;
    public function calcDiscount($purchaseDate)
    {
        if ($purchaseDate->format('D') != "Sun" && $purchaseDate->format('D') != "Mon") {

            $this->discountPercentage = 10;
        }
        return $this->discountPercentage;
    }
}
//appliances class has extra properties and implements abstract method to calculate discount
class Appliances extends Product implements Discount
{
    protected $model;
    protected $productionDate;
    protected $weight;
    protected $discountPercentage = null;
    public function calcDiscount($purchaseDate)
    {
        if ($this->price > 999 && ($purchaseDate->format('D') == "Sat" || $purchaseDate->format('D') == "Sun")) {
            $this->discountPercentage = 7;
        }
        return $this->discountPercentage;
    }
}
//purchase class has property, array of objetcs which are products with their properties and amount of products that are bought
class Purchase
{
    public $products = array();
    public function addProduct($product, $amount)
    {
        array_push($this->products, (object)[
            'item' => $product,
            'amount' => $amount
        ]);
    }
    public function __get($property)
    {

        return $this->$property;
    }
}
//class cashier takes object that is type of purchase and date of purchase- dateTime object
class Cashier
{
    protected $purchase;
    protected $dateOfPurchase;
    protected $subtotal = 0;
    protected $totalDiscount = 0;
    public function __construct($purchase, $date)
    {
        $this->purchase = $purchase;
        $this->dateOfPurchase = $date;
    }
    public function printReceipt()
    {
        echo "<br>";
        echo "Date: " . $this->dateOfPurchase->format('Y-m-d h:i:s') . "<br><br><br>";
        echo "---Products---<br><br>";
        foreach ($this->purchase as $purch) {
            foreach ($purch as $product) {
                echo $product->item->name . " " . $product->item->brand . "<br><br>";
                echo $product->amount . " x " . "$" . $product->item->price . " = " . " $ " . round($product->amount * $product->item->price, 2) . "<br><br>";

                $this->subtotal += round($product->amount * $product->item->price, 2);
                if ($product->item->calcDiscount($this->dateOfPurchase) != null) {
                    $discount = round(round($product->amount * $product->item->price, 2) * $product->item->calcDiscount($this->dateOfPurchase) / 100, 2);
                    $this->totalDiscount += $discount;
                    echo "# discount " . $product->item->calcDiscount($this->dateOfPurchase) . "%" . " -$" . $discount;
                    echo "<br>";
                }
                echo "<br><br><br><br>";
            }
        }
        echo "----------------------------------------<br><br>";
        echo "SUBTOTAL : $" . $this->subtotal . "<br><br>";
        echo "DISCOUNT : -$" . $this->totalDiscount . "<br><br>";
        echo "<br>TOTAL: $" . (($this->subtotal) - ($this->totalDiscount));
    }
}
