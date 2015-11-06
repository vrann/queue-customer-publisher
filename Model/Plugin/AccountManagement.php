<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\QueueCustomerPublisher\Model\Plugin;

class AccountManagement
{
    /**
     * @var \Magento\Framework\MessageQueue\PublisherProxy
     */
    private $publisher;

    /**
     * @var \Psr\Log\LoggerInterface $logger
     */
    private $logger;

    /**
     * @param \Magento\Framework\MessageQueue\PublisherProxy $publisher
     */
    public function __construct(
        \Magento\Framework\MessageQueue\PublisherProxy $publisher,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->publisher = $publisher;
        $this->logger = $logger;
    }

    /**
     * Publish Customer message to the queue before it is persisted
     *
     * @param \Magento\Customer\Api\AccountManagementInterface $subject
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param null $password
     * @param string $redirectUrl
     */
    public function beforeCreateAccount(
        \Magento\Customer\Api\AccountManagementInterface $subject,
        \Magento\Customer\Api\Data\CustomerInterface $customer,
        $password = null,
        $redirectUrl = ''
    ) {
        try {
            $this->publisher->publish(
                'customer.created',
                ['customer' => $customer, 'password' => $password, 'redirect_url' => $redirectUrl]
            );
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
