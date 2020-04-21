<p align="center"><a href="https://snapp.market" target="_blank"><img src="https://snapp.market/static/media/logo.d5ee94bf.png" width="200"></a></p>
<p align="center">
<a href="https://packagist.org/packages/snappmarket/php-rest-communicator"><img src="https://poser.pugx.org/snappmarket/php-rest-communicator/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/snappmarket/php-rest-communicator"><img src="https://poser.pugx.org/snappmarket/php-rest-communicator/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/snappmarket/php-rest-communicator"><img src="https://poser.pugx.org/snappmarket/php-rest-communicator/license.svg" alt="License"></a>
<a href="https://packagist.org/packages/snappmarket/php-rest-communicator"><img src="https://poser.pugx.org/snappmarket/php-rest-communicator/composerlock" alt="License"></a>
</p>
## SnappMarket PHP Rest Communicator For Microservices
This package developed to use as <a href="https://snapp.market">SnappMarket</a> Microservices Rest Communicator.
### Requirements
- `PHP >= 7.2.0`
- `JSON PHP Extension`
### installation
require package inside your `composer.json` file.

`
$ composer require snappmarket/php-rest-communicator
`
### Basic Usage


##### 1. simple GET request with QueryString.
```php
<?php
use SnappMarket\Communicator\Communicator;
use Illuminate\Log\Logger; // This is just an example for laravel logger that implements LoggerInterface
$base_url = 'your_base_ur_here';
$headers = ['x-Foo'=>'Bar'];
$logger = new Logger();
$uri = 'your_uri_here';
$parameters = [
    'page' => '2',
    'sort' => 'desc'
]; // parameters array acts as querystring (https://foo.bar/?page=2&sort=desc)
try {
    $communicator = new Communicator($base_url, $headers, $logger);
    $response = $communicator->request(Communicator::METHOD_GET,$uri,$parameters, $headers);
 } catch (Exception $exception){
    throw $exception;
}
```

##### 2. simple POST request with JSON body.
```php
<?php
use SnappMarket\Communicator\Communicator;
use Illuminate\Log\Logger; // This is just an example for laravel logger that implements LoggerInterface
$base_url = 'your_base_ur_here';
$headers = ['x-Foo'=>'Bar', 'content-type'=>Communicator::APPLICATION_JSON];
$logger = new Logger();
$uri = 'your_uri_here';
$parameters = [
    'phone_number' => '09xxxxxxxxx'
];
try {
    $communicator = new Communicator($base_url, $headers, $logger);
    $response = $communicator->request(Communicator::METHOD_POST,$uri,$parameters, $headers);
 } catch (Exception $exception){
    throw $exception;
}
```