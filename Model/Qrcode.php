<?php

namespace Braspag\BraspagPagador\Model;

use Magento\Framework\Filesystem\DriverPool;

class Qrcode
{

    protected $_mediaDirectory;

    protected $urlInterface;

    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\UrlInterface $urlInterface
    ) {
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA, DriverPool::FILE);
        $this->urlInterface = $urlInterface;
    }
    public function saveToFile(?string $qrCodeContent, ?string $id): string
    {
        if (!$this->_mediaDirectory->isDirectory('braspag_pix')) {
            $this->_mediaDirectory->create('braspag_pix');
        }

        (new \chillerlan\QRCode\QRCode())->render(
            $qrCodeContent,
            $this->_mediaDirectory->getAbsolutePath('braspag_pix') . DIRECTORY_SEPARATOR . $id . md5($qrCodeContent) . '.png'
        );

        return $this->urlInterface->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . "braspag_pix/" . $id . md5($qrCodeContent) . '.png';
    }
}