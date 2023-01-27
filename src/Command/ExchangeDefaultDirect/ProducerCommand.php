<?php
namespace App\Command\ExchangeDefaultDirect;

use App\Infra\Amqp\Connection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProducerCommand extends Command
{
    private const ARG_COUNT = 'count';
    protected static $defaultName = 'app:producer:default_direct';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(self::ARG_COUNT, InputArgument::OPTIONAL, 'Count messages to queue.', 1)
        ;
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Started - producer');

        $queue = 'queue_mq';
        $exchange = '';

        $channel = $this->connection->getConnection()->channel();
        $channel->queue_declare($queue, false, true, false, false);

        $output->writeln('Connection run to queue');
        $count = $input->getArgument(self::ARG_COUNT);
        $output->writeln('Count messages: ' . $count);

        //$channel->queue_bind($queue, $exchange);

        $item = 1;
        while ($item <= $count) {
            $messageBody = json_encode(['item' => $item, 'time' => date(\DateTimeInterface::RFC3339)]);
            $message = new AMQPMessage($messageBody, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
            $channel->basic_publish($message, $exchange, $queue);
            $output->writeln(' [x] Send: ' . $messageBody);
            $item++;
        }

        $channel->close();
        $this->connection->close();
        $output->writeln('Finished - producer');

        return Command::SUCCESS;
    }
}
