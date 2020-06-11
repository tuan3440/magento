<?php


namespace Tuandz\Preference\Block;


use Magento\Framework\View\Element\Template;

class Person extends Template
{
    private $_person;
    public function __construct(\Tuandz\Preference\Model\PersonInterface $person,Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_person = $person;
    }

    public function getInfo()
    {
        return $this->_person->whoAmI();
    }

}
