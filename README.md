```php
    composer require league/omnipay alekseyblymin/omnipay-bitbanker
```

# Примеры использования

## Создание счета
```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('bitBanker');

$gateway->setApiKey('1234');

//Включение тестового режима
$gateway->setTestMode(true);

$response = $gateway->createInvoice([
    "payment_currencies" => ["BTC"],
    "currency" => "RUB",
    "amount" => 5000,
    "description" => "покупка ноутбука модель ACer..",
    "header" => "Фирма",
    "is_convert_payments" => false,
    "data" => "{}",
])->send();

if ($response->isSuccessful()){

    echo $response->getInvoiceId();
    echo $response->getInvoiceLink();
    
} else {

    echo $response->getMessage();
    
}
```

## Получение статуса платежа через вебхук
```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('bitBanker');

$gateway->setApiKey('1234')
        ->setSecretKey('4321');
        
$notification = $gateway->acceptNotification();

//Получение ID счета;
$notification->getInvoiceId();

//Данные которые использовались при создании счета
$notification->setInvoiceRequestData([
    "currency" => "RUB",
    "amount" => 5000,
    "description" => "покупка ноутбука модель ACer..",
    "header" => "Фирма",
]);

//Проверка подписей
if ($notification->check()) {

    //Получение подробной информации об оплате
    print_r($notification->getMessage());
    
    //Получение статуса
    echo $notification->getTransactionStatus();
}
```