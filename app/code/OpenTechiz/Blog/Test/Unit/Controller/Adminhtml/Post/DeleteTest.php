<?php


namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Post;

use PHPUnit\Framework\TestCase;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use Magento\Backend\Model\View\Result\RedirectFactory;
use \Magento\Framework\ObjectManagerInterface;

class DeleteTest extends TestCase
{
    protected $controllerDeletePost;
    protected $requestMock;
    protected $resultRedirectFactoryMock;
    protected $_objetcManagerMock;
    protected $messageManagerMock;

     protected function setUp()
     {
         $this->requestMock = $this->createMock(RequestInterface::class);

         $this->resultRedirectFactoryMock = $this->createMock(RedirectFactory::class);

         $this->_objetcManagerMock = $this->createMock(ObjectManagerInterface::class);

         $this->messageManagerMock = $this->createMock(ManagerInterface::class);


         $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
         $this->controllerDeletePost = $objectManagerHelper->getObject(
             \OpenTechiz\Blog\Controller\Adminhtml\Post\Delete::class,
             [
                 'request' => $this->requestMock,
                 'resultRedirectFactory' => $this->resultRedirectFactoryMock,
                 '_objectManager' => $this->_objetcManagerMock,
                 'messageManager' => $this->messageManagerMock
             ]
         );

     }

     public function testExecuteNotId()
     {

         $this->requestMock->method('getParam')->with('post_id')->willReturn(null);
         $redirect = $this->createMock(Redirect::class);
         $this->resultRedirectFactoryMock->method('create')->willReturn($redirect);
         $this->messageManagerMock->method('addErrorMessage')->with(__('We can\'t find a post to delete.'))->willReturnSelf();
         $redirect->method('setPath')->with('*/*/')->willReturnSelf();

         $this->assertSame($redirect, $this->controllerDeletePost->execute());
     }

     public function testExcuteDeleteSuccess()
     {
         $id = 1;
         $this->requestMock->method('getParam')->with('post_id')->willReturn($id);
         $redirect = $this->createMock(Redirect::class);
         $this->resultRedirectFactoryMock->method('create')->willReturn($redirect);
         $post = $this->createMock(\OpenTechiz\Blog\Model\Post::class);
         $this->_objetcManagerMock->method('create')->willReturn($post);
         $post->method('getTitle')->willReturn('aaa');
         $post->method('delete')->willReturnSelf();
         $this->messageManagerMock->method('addSuccessMessage')->with(__('The Post has been deleted.'))->willReturnSelf();
         $redirect->method('setPath')->with('*/*/')->willReturnSelf();

         $this->assertSame($redirect, $this->controllerDeletePost->execute());



     }

     public function testExecuteThrowException()
     {
         $id = 1;
         $errorMsg = 'Can\'t delete the post';
         $this->requestMock->method('getParam')->with('post_id')->willReturn($id);
         $redirect = $this->createMock(Redirect::class);
         $this->resultRedirectFactoryMock->method('create')->willReturn($redirect);
         $post = $this->createMock(\OpenTechiz\Blog\Model\Post::class);
         $this->_objetcManagerMock->method('create')->willReturn($post);
         $post->method('getTitle')->willReturn('aaa');
         $post->method('delete')->willThrowException(new \Exception(__($errorMsg)));
         $this->messageManagerMock->method('addErrorMessage')->with(__($errorMsg))->willReturnSelf();
         $redirect->method('setPath')->with('*/*/edit', ['post_id' => $id])->willReturnSelf();

         $this->assertSame($redirect, $this->controllerDeletePost->execute());
     }



}
