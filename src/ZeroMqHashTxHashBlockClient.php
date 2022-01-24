<?php

namespace Bitcoin;

use Closure;
use Exception;

class ZeroMqHashTxHashBlockClient
{
    const Q_NAME_HASH_TX = 'hashtx';
    const Q_NAME_HASH_BLOCK = 'hashblock';

    private $handShake = [
        'ff00000000000000017f',
        '03004e554c4c000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'
    ];
    private $socketToServer = null;
    private $chunk = '';
    /**
     * @var string
     */
    private $ip;
    /**
     * @var int
     */
    private $port;
    /**
     * @var string
     */
    private $qName;

    /**
     * ZeroMq constructor.
     * @param string $ip
     * @param int $port
     * @param string $qName
     * @throws Exception
     */
    public function __construct(string $ip, int $port, string $qName)
    {
        if ($qName != self::Q_NAME_HASH_BLOCK && $qName != self::Q_NAME_HASH_TX) {
            throw new Exception("Invalid queue name");
        }
        $this->ip = $ip;
        $this->port = $port;
        $this->qName = $qName;

        $this->openConnection();
        $this->handShake();
    }

    /**
     * @throws Exception
     */
    private function openConnection()
    {
        $socketToServer = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socketToServer === false) {
            throw new Exception("socket_create() failed: reason: " . socket_strerror(socket_last_error()));
        }
        $connectionToServerResult = socket_connect($socketToServer, $this->ip, $this->port);
        if ($connectionToServerResult === false) {
            throw new Exception("socket_connect() failed.\nReason: " . socket_strerror(socket_last_error($socketToServer)));
        }
        $this->socketToServer = $socketToServer;
    }

    /**
     *
     */
    private function handShake(): void
    {
        foreach ($this->handShake as $out) {
            socket_write($this->socketToServer, $out, strlen($out));
            usleep(1000);
            socket_read($this->socketToServer, 2048, PHP_BINARY_READ);
        }
    }


    /**
     * @param int $lenght
     * @return string
     */
    private function readChunk(int $lenght): string
    {
        if (strlen($this->chunk) >= $lenght) {
            $ret = substr($this->chunk, 0, $lenght);
            $this->chunk = substr($this->chunk, $lenght);
            return $ret;
        }
        while ($this->chunk .= socket_read($this->socketToServer, 2048, PHP_BINARY_READ)) {
            if (strlen($this->chunk) >= $lenght) {
                break;
            }
        }
        $ret = substr($this->chunk, 0, $lenght);
        $this->chunk = substr($this->chunk, $lenght);
        return $ret;
    }

    /**
     * @param Closure $function
     */
    public function onMsg(Closure $function): void
    {
        while (true) {
            $this->readChunk(2);
            $this->readChunk(strlen($this->qName));
            $this->readChunk(2);
            $hashBin = $this->readChunk(32);
            $this->readChunk(6);
            $hash = bin2hex(($hashBin));
            $function($hash);
            if (socket_last_error($this->socketToServer)) {
                break;
            }
        }
    }

    private function closeConnetion(): void
    {
        if (!$this->socketToServer) {
            return;
        }
        socket_close($this->socketToServer);
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->closeConnetion();
    }
}