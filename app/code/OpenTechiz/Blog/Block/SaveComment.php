<?php

namespace OpenTechiz\Blog\Block;

class SaveComment extends \Magento\Framework\View\Element\Template
{
    protected $_request;

    protected $_customerSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\RequestInterface $request,
        array $data = []
    )
    {
        $this->_request = $request;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getFormAction()
    {
        return '/magento2/blog/comment/save';
    }

//    public function getAjaxNotificationLoadUrl()
//    {
//        return '/magento2/blog/notification/load';
//    }

    public function getAjaxUrl()
    {
        return '/magento2/blog/comment/load';
    }

    public function getPostId()
    {
        return $this->_request->getParam('id', false);
    }
}
