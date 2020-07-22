<?php


namespace OpenTechiz\Blog\Test\Unit\Controller\Comment;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use PHPUnit\Framework\TestCase;

class SaveTest extends TestCase
{

    protected $controller;
    protected $requestMock;
    protected $commentFactoryMock;

    protected $resultJsonFactoryMock;
    protected $customerSessionMock;
    protected $commentCollectionFactoryMock;

    protected function setUp()
    {
        $this->requestMock = $this->getMockBuilder(RequestInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPostValue'])
            ->getMockForAbstractClass();

        $this->customerSessionMock = $this->createMock(\Magento\Customer\Model\Session::class);
        $this->resultJsonFactoryMock = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setData'])
            ->getMock();

        $this->commentFactoryMock = $this->getMockBuilder(\OpenTechiz\Blog\Model\CommentFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create','addData', 'save'])
            ->getMock();





        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->controller = $objectManagerHelper->getObject(
            \OpenTechiz\Blog\Controller\Comment\Save::class,
            [
                'request' => $this->requestMock,
                'customerSession' => $this->customerSessionMock,
                'resultJsonFactory' => $this->resultJsonFactoryMock,
                'commentFactory' => $this->commentFactoryMock
            ]
        );
    }

    public function testExecuteReturnWhenNotLoggedIn()
    {
       $postData = [
           'comment' => 'aaa',
           'post_id' => 1,
           'customer_id' => 1
       ];

        $dataRequest = $this->requestMock
                                         ->method('getPostValue')
                                         ->willReturn($postData);

        $this->customerSessionMock->method('isLoggedIn')
                                  ->willReturn(false);

        $resultJson = $this->createMock(\Magento\Framework\Controller\Result\Json::class);

        $this->resultJsonFactoryMock
            ->method('create')
            ->willReturn($resultJson);
        $array = [
            'result' => 'true',
            'message' => "You need log in to comment"
        ];

        $expectResult = json_encode($array);

        $this->resultJsonFactoryMock
                                    ->method('setData')
                                    ->with(
                                        $array
                                    )
                                    ->willReturn($expectResult);
        $this->assertJson($expectResult, $this->controller->execute());
    }

    public function testExecuteReturnWhenNotPostData()
    {
        $postData = null;

        $dataRequest = $this->requestMock
            ->method('getPostValue')
            ->willReturn($postData);

        $this->customerSessionMock->method('isLoggedIn')
            ->willReturn(true);

        $resultJson = $this->createMock(\Magento\Framework\Controller\Result\Json::class);

        $this->resultJsonFactoryMock
            ->method('create')
            ->willReturn($resultJson);
        $array = [
            'result' => 'true',
            'message' => "Your submission is not valid. Please try again!"
        ];

        $expectResult = json_encode($array);

        $this->resultJsonFactoryMock
            ->method('setData')
            ->with(
                $array
            )
            ->willReturn($expectResult);
        $this->assertJson($expectResult, $this->controller->execute());
    }

    public function testExecuteReturnWhenSaveSuccess()
    {
        $postData = [
            'comment' => 'aaa',
            'post_id' => 1,
            'customer_id' => 1
        ];

        $customer_id = 1;

        $dataRequest = $this->requestMock
            ->method('getPostValue')
            ->willReturn($postData);

        $this->customerSessionMock->method('isLoggedIn')
            ->willReturn(true);

        $resultJson = $this->createMock(\Magento\Framework\Controller\Result\Json::class);

        $this->resultJsonFactoryMock
            ->method('create')
            ->willReturn($resultJson);

        $comment = $this->createMock(\OpenTechiz\Blog\Model\Comment::class);

        $this->commentFactoryMock->method('create')
                                 ->willReturn($comment);

        $array = [
            'comment' => 'aaa',
            'post_id' => 1,
            'customer_id' => 1,
            'is_active' => 2
        ];

        $this->customerSessionMock->method('getCustomer')->willReturnSelf();
        $this->customerSessionMock->method('getId')->willReturn($customer_id);

        $comment->method('addData')
                ->with($array)
                ->willReturnSelf();

        $comment->method('save')
                ->willReturnSelf();

        $data = [
            'result' => 'success',
            'message' => 'Thank you for your submission. Our Admins will review and approve shortly'
        ];

         $expectResult = json_encode($data);

        $this->resultJsonFactoryMock->method('setData')
                                    ->with($data)
                                    ->willReturn($expectResult);

        $this->assertJson($expectResult, $this->controller->execute());



    }


}
