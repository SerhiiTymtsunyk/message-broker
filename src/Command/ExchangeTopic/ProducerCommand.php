<?php

namespace App\Command\ExchangeTopic;

use App\Infra\Amqp\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProducerCommand extends Command
{
    private const ARG_COUNT = 'count';
    protected static $defaultName = 'app:producer:topic';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(self::ARG_COUNT, InputArgument::OPTIONAL, 'Count messages to queue.', 1);
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Started - producer');

        return Command::SUCCESS;
    }
}
