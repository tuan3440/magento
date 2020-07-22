<?php


namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Post;

use PHPUnit\Framework\TestCase;
use OpenTechiz\Blog\Controller\Adminhtml\Post\Save;
use Magento\Framework\App\RequestInterface;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use OpenTechiz\Blog\Model\Post;

use Magento\Framework\Message\ManagerInterface;
use \Magento\Backend\Model\Session;

class SaveTest extends TestCase
{
    protected $saveMock;
    protected $requestMock;
    protected $postFactoryMock;
    protected $resultRedirectFactoryMock;
    protected $messageManagerMock;


    protected function setUp()
    {
        $this->requestMock = $this->getMockBuilder(RequestInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPostValue'])
            ->getMockForAbstractClass();
        $this->resultRedirectFactoryMock = $this->createMock(RedirectFactory::class);
        $this->postFactoryMock = $this->getMockBuilder(\OpenTechiz\Blog\Model\PostFactory::class)
                ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();



        $this->messageManagerMock = $this->createMock(ManagerInterface::class);

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->saveMock = $objectManagerHelper->getObject(
            Save::class,
            [
                'request' => $this->requestMock,
                'resultRedirectFactory' => $this->resultRedirectFactoryMock,

                'messageManager' => $this->messageManagerMock,
                'postFactory' => $this->postFactoryMock
            ]
        );

    }

    public function testExecuteNotData()
    {
//        $data = [
//            'post_id' => 1,
//            'title' => 'a',
//            'content' => 'a',
//            'url_key' => 'aaa',
//            'is_active' => 1
//        ];

        $this->requestMock->method('getPostValue')->willReturn(null);
        $resultRedirect = $this->createMock(Redirect::class);
        $this->resultRedirectFactoryMock->method('create')->willReturn($resultRedirect);
        $post = $this->createMock(Post::class);
        $this->postFactoryMock->method('create')->willReturn($post);
        $resultRedirect->method('setPath')->with('*/*/')->willReturnSelf();

        $this->assertSame($resultRedirect, $this->saveMock->execute());


    }

    public function testExecuteSaveSuccess()
    {
        $data = [
            'post_id' => 1,
            'title' => 'a',
            'content' => 'a',
            'url_key' => 'aaa',
            'is_active' => 1
        ];

        $this->requestMock->method('getPostValue')->willReturn($data);
        $resultRedirect = $this->createMock(Redirect::class);
        $this->resultRedirectFactoryMock->method('create')->willReturn($resultRedirect);
        $post = $this->createMock(Post::class);
        $post->method('getID')->willReturn(1);
        $this->postFactoryMock->method('create')->willReturn($post);
        $post->method('load')->with(1)->willReturnSelf();
        $post->method('setTitle')->with('a')->willReturnSelf();
        $post->method('setUrlKey')->with('aaa')->willReturnSelf();
        $post->method('setContent')->with('a')->willReturnSelf();
        $post->method('setIsActive')->with(1)->willReturnSelf();
        $post->method('save')->willReturnSelf();
        $this->messageManagerMock->method('addSuccess')->with(__('You saved this Post.'))->willReturnSelf();
        $this->requestMock->method('getParam')->with('back')->willReturn(1);
        $resultRedirect->method('setPath')->with('*/*/edit', ['post_id' => $data['post_id'], '_current' => true])->willReturnSelf();

        $this->assertSame($resultRedirect, $this->saveMock->execute());

    }

    public function testExecuteThrowException()
    {
        $msgError = "Error";
        $data = [
            'post_id' => 1,
            'title' => 'a',
            'content' => 'a',
            'url_key' => 'aaa',
            'is_active' => 1
        ];

        $this->requestMock->method('getPostValue')->willReturn($data);
        $this->requestMock->method('getParam')->with('post_id')->willReturn(1);
        $resultRedirect = $this->createMock(Redirect::class);
        $this->resultRedirectFactoryMock->method('create')->willReturn($resultRedirect);
        $post = $this->createMock(Post::class);
        $post->method('getID')->willReturn(1);
        $this->postFactoryMock->method('create')->willReturn($post);
        $post->method('load')->with(1)->willReturnSelf();
        $post->method('setTitle')->with('a')->willReturnSelf();
        $post->method('setUrlKey')->with('aaa')->willReturnSelf();
        $post->method('setContent')->with('a')->willReturnSelf();
        $post->method('setIsActive')->with(1)->willReturnSelf();
        $post->method('save')->willThrowException(new \Exception($msgError));
        $this->messageManagerMock->method('addError')->with($msgError)->willReturnSelf();
        $resultRedirect->method('setPath')->with('*/*/edit', ['post_id' => 1])->willReturnSelf();

        $this->assertSame($resultRedirect, $this->saveMock->execute());
    }
    //question :
    //1,PostFactory
    //2,ObjectManagerHelper
    //3,resultRedirectFactory
    //\Magento\Framework\Controller\Result\RedirectFactory
    //Magento\Backend\Model\View\Result\RedirectFactory;

}
