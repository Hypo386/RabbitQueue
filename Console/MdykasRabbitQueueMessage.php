<?php

namespace Mdykas\RabbitQueue\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Json\Helper\Data as JsonHelper;

/**
 * Class MdykasRabbitQueueMessage for publishing messages
 */
class MdykasRabbitQueueMessage extends Command
{
    /**
     * Message key word
     */
    const MESSAGE = 'message';

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    private $jsonHelper;

    /**
     * @var \Magento\Framework\MessageQueue\PublisherInterface
     */
    private $publisher;

    /**
     * @param JsonHelper $jsonHelper
     * @param \Magento\Framework\MessageQueue\PublisherInterface $publisher
     */
    public function __construct(
        JsonHelper $jsonHelper,
        \Magento\Framework\MessageQueue\PublisherInterface $publisher
    ) {
        parent::__construct();
        $this->jsonHelper = $jsonHelper;
        $this->publisher = $publisher;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                self::MESSAGE,
                null,
                InputOption::VALUE_REQUIRED,
                'Message'
            )
        ];

        $this->setName('mdykas:rabbitMessage')
            ->setDescription('Rabbit message command line')
            ->setDefinition($options);

        parent::configure();
    }

    /**
     * Execute console command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return $this|int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($message = $input->getOption(self::MESSAGE)) {
            $output->writeln("Message " . $message);
        } else {
            $output->writeln("Please provide some message");
        }

        try {
            $publishData = ['messageText' => $message];

            $this->publisher->publish('mdykasRabbitQueue.topic', $this->jsonHelper->jsonEncode($publishData));
            $result = ['msg' => 'Success'];
            $output->writeln($result);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage()];
            $output->writeln($result);
        }

        return $this;
    }
}
