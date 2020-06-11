<?php


namespace Tuandz\Hello\Plugin\Model;


class Cart
{

    public  function beforeAddProduct($subject, $productInfo, $requestInfo)
    {
        $requestInfo['qty'] = 10;
        return [$productInfo, $requestInfo];
    }

}
