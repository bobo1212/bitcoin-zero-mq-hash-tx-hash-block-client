# Bitcoin ZeroMq PHP client only for hashtx and hashblock.

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

and run ```composer require bobo1212/zero-mq-hash-tx-hash-block-client``` in your project directory.

## Config bitcoind 

Add following lines to bitcoin.conf

```
zmqpubhashblock=tcp://127.0.0.1:28335
zmqpubhashtx=tcp://127.0.0.1:28334
```