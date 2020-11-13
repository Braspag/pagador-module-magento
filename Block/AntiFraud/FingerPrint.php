<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Block\AntiFraud;


use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Webjump\BraspagPagador\Api\Data\AntiFraudFingerPrintInterface;
class FingerPrint extends Template
{
    protected $fingerPrint;

    /**
     * FingerPrint constructor.
     * @param Context $context
     * @param array $data
     * @param AntiFraudFingerPrintInterface $antiFraudFingerPrint
     */
    public function __construct(Context $context, array $data, AntiFraudFingerPrintInterface $antiFraudFingerPrint)
    {
        parent::__construct($context, $data);
        $this->setFingerPrint($antiFraudFingerPrint);
    }

    public function getSrcParams()
    {
        $data = [
            'org_id' => $this->getFingerPrint()->getOrgId(),
            'session_id' => $this->getFingerPrint()->getSessionId(),
        ];

        return http_build_query($data);
    }

    /**
     * @return AntiFraudFingerPrintInterface
     */
    public function getFingerPrint()
    {
        return $this->fingerPrint;
    }

    /**
     * @param AntiFraudFingerPrintInterface $antiFraudFingerPrint
     * @return $this
     */
    public function setFingerPrint(AntiFraudFingerPrintInterface $antiFraudFingerPrint)
    {
        $this->fingerPrint = $antiFraudFingerPrint;
        return $this;
    }
}
