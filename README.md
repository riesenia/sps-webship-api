# SPS Webship API

SPS Webship API PHP client implementation. See [webservice documentation](http://www.solver.sk/webship/webshipApiDokumentaciaEN.pdf) for details.

## Installation

Install the latest version using `composer require riesenia/sps-webship-api`

## Usage

Create API with username and password

```php
use Riesenia\SpsWebship\Api;

$api = new Api($username, $password);
```

### Create shipment

```php
$data = [
    'cod' => [
        'codvalue' => 12.30
    ],
    'insurvalue' => 12.30,
    'notifytype' => 1,
    ...
];

if (!$api->createShipment($shipment)) {
    echo $api->getMessages();
}
```

### Print shipment labels

```php
$url = $api->printShipmentLabels();

if (!$url) {
    echo $api->getMessages();
}
```

### Print end of day report

```php
$url = $api->printEndOfDay();

if (!$url) {
    echo $api->getMessages();
}
```
