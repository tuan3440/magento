<?php


namespace Tuandz\Hello\Plugin\Block\Page;


class Header
{
     public function afterSetHeader($subject, $result)
     {
         $result .= 'Duong';
         return $result;
     }
}
