<?php
namespace Tuandz\HelloWorld\Block;
class Display extends \Magento\Framework\View\Element\Template
{
    protected $_postFactory;
    protected $_postCollectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tuandz\HelloWorld\Model\PostFactory $postFactory,
        \Tuandz\HelloWorld\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory
    )
    {
        $this->_postCollectionFactory = $postCollectionFactory;
        parent::__construct($context);
    }

    public function sayHello()
    {
        return __('Hello World');
    }

    public function getPostCollection(){
//        $post = $this->_postFactory->create();
//        return $post->getCollection();
        $collection = $this->_postCollectionFactory->create();

        return $collection;
    }
}
