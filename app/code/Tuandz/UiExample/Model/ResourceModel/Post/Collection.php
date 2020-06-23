<?php
namespace Tuandz\UiExample\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'employee_details_collection';
    protected $_eventObject = 'post_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Tuandz\UiExample\Model\Post', 'Tuandz\UiExample\Model\ResourceModel\Post');
    }

}
