<?php

namespace App\Infra\Amqp;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class MakeConnection implements Connection
{
    private AMQPStreamConnection $amqpConnection;

    /**
     * @throws \Exception
     */
    public function __construct(string $amqpHost, int $amqpPort, string $amqpUser, string $amqpPass)
    {
        $this->amqpConnection = new AMQPStreamConnection($amqpHost, $amqpPort, $amqpUser, $amqpPass);
    }

    public function getConnection(): AMQPStreamConnection
    {
        return $this->amqpConnection;
    }

    /**
     * @throws \Exception
     */
    public function close()
    {
        $this->amqpConnection->close();
    }
}
