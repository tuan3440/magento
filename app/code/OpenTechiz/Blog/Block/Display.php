<?php


namespace OpenTechiz\Blog\Block;
use OpenTechiz\Blog\Model\ResourceModel\Post\Collection as PostCollection;
use OpenTechiz\Blog\Api\Data\PostInterface;
class Display extends \Magento\Framework\View\Element\Template
{
//    protected $_postFactory;
    protected $_postCollectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \OpenTechiz\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory

    )
    {
        $this->_postCollectionFactory = $postCollectionFactory;
        parent::__construct($context);
    }

    public function sayHello()
    {
        return __('Hello World');
    }

    public function getPosts()
    {
        if (!$this->hasData('posts')) {
            $posts = $this->_postCollectionFactory
                ->create()
                ->addFilter('is_active', 1)
                ->addOrder(
                    PostInterface::CREATION_TIME,
                    PostCollection::SORT_ORDER_DESC
                );
            $this->setData('posts', $posts);
        }
        return $this->getData('posts');
    }

//    public function getPostCollection(){
//        $post = $this->_postFactory->create();
//        return $post->getCollection();
//
//
//
//    }
}

