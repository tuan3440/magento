<?php


namespace OpenTechiz\Blog\Test\Unit\Controller\Comment;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use PHPUnit\Framework\TestCase;

class LoadTest extends TestCase
{
    protected $controller;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultJsonFactoryMock;

    /**
     * @var \OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $commentCollectionFactoryMock;

    /**
     * @var \Magento\Customer\Model\Session|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $customerSessionMock;

    /**
     * @var RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    protected function setUp()
    {
        $this->requestMock = $this->getMockBuilder(RequestInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPostValue'])
            ->getMockForAbstractClass();

        $this->customerSessionMock = $this->createMock(\Magento\Customer\Model\Session::class);

        $this->commentCollectionFactoryMock = $this->getMockBuilder(\OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create','addFieldToFilter', 'addOrder', 'toArray'])
            ->getMock();

        $this->resultJsonFactoryMock = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->controller = $objectManagerHelper->getObject(
            \OpenTechiz\Blog\Controller\Comment\Load::class,
            [
                'request' => $this->requestMock,
                'customerSession' => $this->customerSessionMock,
                'commentCollectionFactory' => $this->commentCollectionFactoryMock,
                'resultJsonFactory' => $this->resultJsonFactoryMock
            ]
        );
    }

    public function testExcuteReturnFalseWhenNotLogIn()
    {
        $this->customerSessionMock->method('isLoggedIn')->willReturn(false);
        $this->assertFalse($this->controller->execute());
    }

    public function testDataHasLoadedWhenExecute()
    {
        $id = 1;
        $postData = [
            'post_id' => 1
        ];

        $data = [
            'totalRecords' => 2,
            'post_id' => 1,
            'content' => 'this is content test'
        ];

//        $expectResult = '{
//          "totalRecords" : 2,
//            "post_id" : 1,
//            "content" : "this is content test"
//    }';

        $expectResult = json_encode($data);


        $this->customerSessionMock->method('isLoggedIn')->willReturn(true);

        $dataRequest = $this->requestMock->expects($this->once())
            ->method('getPostValue')
            ->willReturn($postData);

        $userId = $this->customerSessionMock->method('getCustomerId')->willReturn($id);

        $commentCollection = $this->createMock(\OpenTechiz\Blog\Model\ResourceModel\Comment\Collection::class);
        $this->commentCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($commentCollection);


        $commentCollection->expects($this->at(0))
            ->method('addFieldToFilter')
            ->with('post_id', 1)
            ->willReturnSelf();

        $commentCollection->expects($this->at(1))
            ->method('addFieldToFilter')
            ->with('is_active', 1)
            ->willReturnSelf();

        $commentCollection->expects($this->any())
            ->method('addOrder')
            ->with('created_at', 'DESC')
            ->willReturnSelf();

        $commentCollection->expects($this->any())
            ->method('toArray')
            ->willReturn($data);

        $resultJson = $this->createMock(\Magento\Framework\Controller\Result\Json::class);

        $this->resultJsonFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultJson);

        $resultJson->expects($this->once())
                   ->method('setData')
                   ->with($data)
                   ->willReturn($expectResult);




        $this->assertJson($expectResult, $this->controller->execute());



    }

}
