<?php


namespace Tuandz\CustomRoute\Controller\Index;


use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class Index extends Action
{


    public function execute()
    {
      echo 'index in module customroute';
    }
}
