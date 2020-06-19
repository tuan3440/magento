<?php


namespace Tuandz\Hello\Block\Page;



use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Index extends Template
{



    public function sayHello()
     {
         return "mGENTO";
     }

     public function setHeader($subheader = "")
     {
         $title = "no pain no gain";
         if($subheader) {
             $title .= $subheader;
         }

         return $title;
     }
}
