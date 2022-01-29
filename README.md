# Bitcoin ZeroMq PHP client for hashtx and hashblock.

## Installation with Composer

Add following lines to composer.json
```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/bobo1212/bitcoin-zero-mq-hash-tx-hash-block-client.git"
        }
    ]
```

and run ```composer require bobo1212/bitcoin-zero-mq-hash-tx-hash-block-client``` in your project directory.

## Config bitcoind 

Add following lines to bitcoin.conf

```ini
zmqpubhashtx=tcp://127.0.0.1:28334
zmqpubhashblock=tcp://127.0.0.1:28335
```

## Consume  hashtx
```php
/**
 * Include composer autoloader by uncommenting line below
 * if you're not already done it anywhere else in your project.
 **/

//require 'vendor/autoload.php';

use Bitcoin\ZeroMqHashTxHashBlockClient;

$client = new ZeroMqHashTxHashBlockClient('localhost', 28334, ZeroMqHashTxHashBlockClient::Q_NAME_HASH_TX);
$client->onMsg(function ($hashtx) {
    echo 'hashtx: ' . $hashtx . "\n";
});
```

## Consume  hashblock
```php
/**
* Include composer autoloader by uncommenting line below
* if you're not already done it anywhere else in your project.
  **/

//require 'vendor/autoload.php';

use Bitcoin\ZeroMqHashTxHashBlockClient;

$client = new ZeroMqHashTxHashBlockClient('localhost', 28335, ZeroMqHashTxHashBlockClient::Q_NAME_HASH_BLOCK);
$client->onMsg(function ($hashblock) {
    echo 'hashblock: ' . $hashblock . "\n";
});
```
## Donations
If you like this project, please consider donating:<br>
**BTC**: 38kXJgKubEEojpzQe91T3dU6BKiwgN2euo<br>
<p>
  <img src="assets/qrcode.png">
</p>
❤Thanks for your support!❤


## Contact
bobo1212@wp.pl