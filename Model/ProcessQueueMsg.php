<?php

namespace Mdykas\RabbitQueue\Model;

use Magento\Framework\MessageQueue\EnvelopeInterface;

/**
 * ProcessQueueMsg Model
 */
class ProcessQueueMsg
{
    /**
     * Process a message
     *
     * @param EnvelopeInterface $message
     * @return string
     */
    public function process(EnvelopeInterface $message)
    {
        $message->getBody();

        usleep(10000000);

        return true;
    }
}
