<?php

// This file is your starting point (= since it's the index)
// It will contain most of the logic, to prevent making a messy mix in the html

// This line makes PHP behave in a more strict way
declare(strict_types=1);

// We are going to use session variables so we need to enable sessions
session_start();

// Use this function when you need to need an overview of these variables
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

// TODO: provide some products (you may overwrite the example)
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
    // TODO: This function will send a list of invalid fields back
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
    // TODO: form related tasks (step 1)
    $_SESSION['formData'] = $_POST;

    // Validation (step 2)
    $invalidFields = validate();
    if (!empty($invalidFields)) {
        // TODO: handle errors
        foreach ($invalidFields as $field){
            echo "<div class='alert alert-danger mt-3'>$field is invalid or empty</div>";
        }
    } else {
        if (isset($_SESSION['formData']['products']) && is_array($_SESSION['formData']['products'])){
            echo "<div class='alert alert-success mt-3'>Order placed successfully! Chosen products: " . implode(', ', $_SESSION['formData']['products']) . " | Delivery Address: {$_SESSION['formData']['street']} {$_SESSION['formData']['streetnumber']}, {$_SESSION['formData']['city']} {$_SESSION['formData']['zipcode']}</div>";
        } else{
            echo "<div class='alert alert-success mt-3'>Order placed successfully! | Delivery Address: {$_SESSION['formData']['street']} {$_SESSION['formData']['streetnumber']}, {$_SESSION['formData']['city']} {$_SESSION['formData']['zipcode']}</div>";        
        }
        // TODO: handle successful submission

        $_SESSION['formData'] = [];
    }
}

// TODO: replace this if by an actual check for the form to be submitted
$formSubmitted = !empty($_POST);
if ($formSubmitted) {
    handleForm();
}

require 'form-view.php';