<?php
return [
    'methods' => [
        'orange_money'   => ['label' => 'Orange Money', 'type' => 'mobile_money'],
        'mpesa'          => ['label' => 'M-Pesa', 'type' => 'mobile_money'],
        'airtel_money'   => ['label' => 'Airtel Money', 'type' => 'mobile_money'],
        'carte_bancaire' => ['label' => 'Carte Bancaire', 'type' => 'card'],
    ],
    'mobile_money' => ['orange_money','mpesa','airtel_money'],
];
