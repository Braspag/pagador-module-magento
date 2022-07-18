<?php

namespace Braspag\BraspagPagador\Helper;

use DateTimeZone;
use Magento\Framework\App\Helper\AbstractHelper;
use Braspag\BraspagPagador\Model\Config\ConfigInterface;

class Pix extends AbstractHelper
{
    protected $config;

    /**
     * Locale Date/Timezone
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        ConfigInterface $config
    ) {
        parent::__construct($context);
        $this->config = $config;
        $this->timezone = $timezone;
    }
    /**
     * @param \Magento\Sales\Model\Order $order
     * @param string $format
     */
    public function prepareExpirationDate($order, $format = "H:i:s Y-d-m"): string
    {
        return $this->getExpirationDate($order)->format($format);
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     */
    public function getExpirationDate($order)
    {
        $orderCreatedAt = new \DateTime($order->getCreatedAt());
        $pixExpiration = $this->config->getPixExpirationTime();
        $min = (null == $pixExpiration ? '86400' : $pixExpiration);
        $minExpiration = 'PT' . $min . 'M';
        $orderCreatedAt->add(new \DateInterval($minExpiration));
        $orderCreatedAt->setTimezone(new \DateTimeZone($this->timezone->getConfigTimezone('store')));

        return $orderCreatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCurrentDate()
    {
        $currentDate = new \DateTime('now', new \DateTimeZone($this->timezone->getConfigTimezone('store')));
        return $currentDate;
    }
}