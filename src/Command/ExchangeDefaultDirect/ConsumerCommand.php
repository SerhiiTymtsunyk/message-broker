<?php
namespace App\Command\ExchangeDefaultDirect;

use App\Infra\Amqp\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumerCommand extends Command
{
    protected static $defaultName = 'app:consumer:default_direct';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Started - consumer');

        $queue = 'queue_mq';
        $exchange = '';

        $channel = $this->connection->getConnection()->channel();
        $channel->queue_declare($queue, false, true, false, false);


        $callback = function ($msg) use ($output) {
            $output->writeln(' [x] Received: ' . $msg->body);
        };

        $channel->basic_consume($queue, '', false, true, false, false, $callback);

        while ($channel->is_open()) {
            $channel->wait();
        }

        $channel->close();
        $this->connection->close();

        $output->writeln('Finished - consumer');
        return Command::SUCCESS;
    }
}
