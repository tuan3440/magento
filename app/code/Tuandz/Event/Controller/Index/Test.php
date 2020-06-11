<?php


namespace Tuandz\Event\Controller\Index;


class Test extends \Magento\Framework\App\Action\Action
{

//    public function execute()
//    {
//        echo "Hello World";
//        exit;
//    }

    public function execute()
    {
        $textDisplay = new \Magento\Framework\DataObject(array('text' => 'Mageplaza'));
        $this->_eventManager->dispatch('mageplaza_helloworld_display_text', ['mp_text' => $textDisplay]);
        echo $textDisplay->getText();
        exit;
    }
}
