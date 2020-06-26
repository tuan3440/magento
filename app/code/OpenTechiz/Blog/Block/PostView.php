<?php


namespace OpenTechiz\Blog\Block;
use OpenTechiz\Blog\Model\ResourceModel\Post\Collection as PostCollection;
use OpenTechiz\Blog\Api\Data\PostInterface;
class PostView extends \Magento\Framework\View\Element\Template
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
        $data = $this->_request->getParams();
        $id = $data['id'];
        $post = $this->_postCollectionFactory->create();
        $post->addFieldToFilter('post_id', $id);
        return $post;
    }

//    public function getPostCollection(){
//        $post = $this->_postFactory->create();
//        return $post->getCollection();
//
//
//
//    }
}
