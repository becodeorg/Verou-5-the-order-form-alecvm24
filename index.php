<?php

declare(strict_types=1);

session_start();

function whatIsHappening() {
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

$products = [
    ['name' => 'Time', 'price' => 24],
    ['name' => 'Energy', 'price' => 69.99],
    ['name' => 'Social Life', 'price' => 419.99],
    ['name' => 'Money', 'price' => 1000000],
    ['name' => 'ALL OF THE ABOVE', 'price' => 0.011010010110110101101101011011010111100101110010011011110110110001100101],//binary for impossible
];

$totalValue = 0;

function validate()
{
    $invalidFields = [];

    $requiredFields = ['email', 'street', 'streetnumber', 'city', 'zipcode'];
    foreach ($requiredFields as $field){
        if (empty($_POST[$field])){
            $invalidFields[] = $field;
        }
    }

    if (!ctype_digit($_POST['zipcode'])){
        $invalidFields[] = 'zipcode';
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $invalidFields = 'email';
    }
    
    return $invalidFields;
}

function handleForm()
{
    global $totalValue;

    $_SESSION['formData'] = $_POST;

    $invalidFields = validate();
    if (!empty($invalidFields)) {
        foreach ($invalidFields as $field){
            echo "<div class='alert alert-danger mt-3'>$field is invalid or empty</div>";
        }
    } else {
        $totalValue = 0;
        if (isset($_SESSION['formData']['products']) && is_array($_SESSION['formData']['products'])){
            foreach ($_SESSION['formData']['products'] as $productIndex){
                if(isset($GLOBALS['products'][$productIndex]['price'])){
                    $totalValue += $GLOBALS['products'][$productIndex]['price'];
                }
            }
        }

        if(isset($_SESSION['formData']['products']) && is_array($_SESSION['formData']['products'])){
            $_SESSION['confirmationMessage'] = "Order placed successfully!";
        }

        if(isset($_SESSION['formData']['products']) && is_array($_SESSION['formData']['products'])){
            echo "<div class='alert alert-success mt-3'>Order placed successfully! Chosen products: " . implode(', ', $_SESSION['formData']['products']) . " | Delivery Address: {$_SESSION['formData']['street']} {$_SESSION['formData']['streetnumber']}, {$_SESSION['formData']['city']} {$_SESSION['formData']['zipcode']}</div>";
        } else{
            echo "<div class='alert alert-success mt-3'>Order placed successfully! | Delivery Address: {$_SESSION['formData']['street']} {$_SESSION['formData']['streetnumber']}, {$_SESSION['formData']['city']} {$_SESSION['formData']['zipcode']}</div>";        
        }

        $_SESSION['formData'] = [];
    }
}

$formSubmitted = !empty($_POST);
if ($formSubmitted) {
    handleForm();
}

require 'form-view.php';