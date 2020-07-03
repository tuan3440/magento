<?php


namespace OpenTechiz\Blog\Controller\JoinTable;


class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $postFactory;
    protected $_resource;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        \OpenTechiz\Blog\Model\PostFactory $postFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->postFactory = $postFactory;
        $this->_resource = $resource;
        return parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->postFactory->create()->getCollection();
        $comment = $this->_resource->getTableName('opentechiz_blog_comment');

        $collection->getSelect()->join(array('comment' => $comment), 'main_table.post_id = comment.post_id');

        print_r($collection->getSelect());
        die;

    }
}

