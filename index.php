<?php

require_once 'classes.php';


//Creating objects/products
$food = new Food('apples', 'BrandA', 1.50);
$food->expirationDate = new DateTime('2021-06-14');


$beverage = new Beverage('milk', 'BrandM', 0.99);
$beverage->expirationDate = new DateTime('2022-02-02');


$clothes = new Clothes('T-shirt', 'BrandT', 15.99);
$clothes->size = 'M';
$clothes->color = 'violet';


$appliance = new Appliances('laptop', 'BrandL', 2345);
$appliance->model = 'ModelL';
$appliance->productionDate = new DateTime('2021-03-03');
$appliance->weight = 1.125;


$cart = new Purchase;
$cart->addProduct($food, 2.45);
$cart->addProduct($beverage, 3);
$cart->addProduct($clothes, 2);
$cart->addProduct($appliance, 1);

//creating date of purchase

$purchaseDate = new DateTime('2021-06-15 12:34:56');

//creating cashier object that has print receipt method

$cashier = new Cashier($cart, $purchaseDate);
$cashier->printReceipt();
