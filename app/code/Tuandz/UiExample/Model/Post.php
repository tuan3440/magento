<?php
namespace Tuandz\UiExample\Model;
class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'employee_details';

    protected $_cacheTag = 'employee_details';

    protected $_eventPrefix = 'employee_details';

    protected function _construct()
    {
        $this->_init('Tuandz\UiExample\Model\ResourceModel\Post');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
