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

$response = $gateway->purchase([
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
        
$response = $gateway->completePurchase();

//Получение ID счета;
$response->getTransactionId();

//Данные которые использовались при создании счета
$response->setRequestData([
    "currency" => "RUB",
    "amount" => 5000,
    "description" => "покупка ноутбука модель ACer..",
    "header" => "Фирма",
]);

//Проверка подписей
if ($response->check()) {

    if ($response->isSuccessful()) {
        print_r($response->getAmount());
        print_r($response->getCurrency());
    }

}
```