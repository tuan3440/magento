<?php


namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Comment;
use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\ObjectManager\ObjectManager;
use OpenTechiz\Blog\Controller\Adminhtml\Comment\Delete;
use OpenTechiz\Blog\Model\Comment;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class DeleteTest extends TestCase
{
      protected $deleteController;
      protected $requestMock;
      protected $resultRedirectMock;
      protected $resultRedirectFactoryMock;
      protected $commentMock;
      protected $objectManager;
      protected $objectManagerMock;
      protected $messageManagerMock;

      protected function setUp()
      {
          $this->requestMock = $this->getMockBuilder(RequestInterface::class)
              ->disableOriginalConstructor()
              ->setMethods(['getParam'])
              ->getMockForAbstractClass();


          $this->resultRedirectMock = $this->getMockBuilder(Redirect::class)
                                           ->disableOriginalConstructor()
                                           ->setMethods(['setPath'])
                                           ->getMock();




          $this->resultRedirectFactoryMock = $this->getMockBuilder(RedirectFactory::class)
                                                  ->disableOriginalConstructor()
                                                  ->setMethods(['create'])
                                                  ->getMock();

          $this->commentMock = $this->getMockBuilder(Comment::class)
                                    ->disableOriginalConstructor()
                                    ->setMethods(['load', 'getTitle', 'delete'])
                                    ->getMock();

          $this->objectManagerMock = $this->getMockBuilder(ObjectManager::class)
                                          ->disableOriginalConstructor()
                                          ->setMethods(['create'])
                                          ->getMock();

          $this->messageManagerMock = $this->getMockBuilder(ManagerInterface::class)
                                           ->disableOriginalConstructor()
                                           ->setMethods(['addSuccessMessage', 'addErrorMessage'])
                                           ->getMockForAbstractClass();

          $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
          $this->deleteController = $this->objectManager->getObject(
              Delete::class,
              [
                  'request' => $this->requestMock,
                  'resultRedirectFactory' => $this->resultRedirectFactoryMock,
                  '_objectManager' => $this->objectManagerMock,
                  'messageManager' => $this->messageManagerMock

              ]
          );


          $this->resultRedirectFactoryMock->method('create')
              ->willReturn($this->resultRedirectMock);




      }

      public function testDeleteExecuteNotId()
      {

          $this->requestMock->method('getParam')
                            ->with('comment_id')
                            ->willReturn(null);



          $this->messageManagerMock->method('addErrorMessage')
                                   ->with(__('We can\'t find a comment to delete.'))
                                   ->willReturnSelf();

          $this->resultRedirectMock->method('setPath')
                                   ->with('*/*/')
                                   ->willReturnSelf();

          $this->assertSame($this->resultRedirectMock, $this->deleteController->execute());

      }

      public function testDeleteExecuteSuccess()
      {
          $id = 1;
          $title = "Tuandz";

          $this->requestMock->method('getParam')
              ->with('comment_id')
              ->willReturn($id);

          $this->objectManagerMock->method('create')
                                  ->willReturn($this->commentMock);

          $this->commentMock->method('load')
                            ->with($id)
                            ->willReturnSelf();

          $this->commentMock->method('getTitle')
                            ->willReturn($title);

          $this->commentMock->method('delete')
                            ->willReturnSelf();

          $this->messageManagerMock->method('addSuccessMessage')
                                   ->with(__('The Comment has been deleted.'))
                                   ->willReturnSelf();

          $this->resultRedirectMock->method('setPath')
                                   ->with('*/*/')
                                   ->willReturnSelf();


          $this->assertSame($this->resultRedirectMock, $this->deleteController->execute());

      }

      public function testDeleteExcuteThrowException()
      {
          $id = 1;
          $title = "Tuandz";
          $errorMsg = 'Can\'t delete the comment';

          $this->requestMock->method('getParam')
              ->with('comment_id')
              ->willReturn($id);

          $this->objectManagerMock->method('create')
              ->willReturn($this->commentMock);

          $this->commentMock->method('load')
              ->with($id)
              ->willReturnSelf();

          $this->commentMock->method('getTitle')
              ->willReturn($title);

          $this->commentMock->method('delete')
                            ->willThrowException(new Exception(__($errorMsg)));

          $this->messageManagerMock->method('addErrorMessage')
                                   ->with($errorMsg);


          $this->resultRedirectMock
              ->method('setPath')
              ->with('*/*/edit', ['comment_id' => $id])
              ->willReturnSelf();

          $this->assertSame($this->resultRedirectMock, $this->deleteController->execute());
      }
}



