<?php

namespace App\Infra\Amqp;

use PhpAmqpLib\Connection\AMQPStreamConnection;

interface Connection
{
    public function getConnection(): AMQPStreamConnection;
    public function close();
}
