<?php
namespace Ecommistry\Canonical\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Page\Config;

/**
 * Class LayoutLoadBefore
 * @package Ecommistry\Canonical\Observer
 */
class LayoutLoadBefore implements ObserverInterface
{
    /**
     * @var Config
     */
    protected $page;

    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    /**
     * LayoutLoadBefore constructor.
     * @param Config $page
     * @param UrlInterface $urlInterface
     */
    public function __construct(Config $page, UrlInterface $urlInterface)
    {
        $this->page             =   $page;
        $this->urlInterface     =   $urlInterface;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $action = $observer->getEvent()->getFullActionName();
        if ($action == 'cms_page_view' || $action == 'cms_index_index') {
            $canonicalUrl= $this->urlInterface->getCurrentUrl();
            $canonicalUrl = rtrim($canonicalUrl, '/');
            $this->page->addRemotePageAsset(
                $canonicalUrl,
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
        }
        return $this;
    }
}