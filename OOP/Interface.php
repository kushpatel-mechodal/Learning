<?php

interface payment
{
    public function pay($amount);
    public function methods();
}

class card implements payment
{
    public function pay($amount)
    {
        echo "You can pay this $amount using";
        echo "<br/>";
    }

    public function methods()
    {
        echo "Creditcard, Debitcard";
        echo "<br/>";
    }
}

class UPI implements payment
{
    public function pay($amount)
    {
        echo "You can pay this $amount using";
        echo "<br/>";
    }

    public function methods()
    {
        echo "Gpay, Phonepay, Paytm";
        echo "<br/>";
    }
}


$card = new card();
$card->pay(5000);
$card->methods();

$upi = new upi();
$upi->pay(2000);
$upi->methods();

?>