<?php


namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Comment;

use PHPUnit\Framework\TestCase;
use Magento\Ui\Component\MassAction\Filter;
use OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\ResultFactory;
use OpenTechiz\Blog\Controller\Adminhtml\Comment\MassDisable;
use OpenTechiz\Blog\Model\ResourceModel\Comment\Collection;
use Magento\Backend\Model\View\Result\Redirect;
class MassDisableTest extends TestCase
{
    protected $filterMock;
    protected $collectionFactoryMock;
    protected $messageManagerMock;
    protected $resultFactoryMock;
    protected $objectManager;
    protected $controllerMassDisable;

      protected function setUp()
      {

          $this->filterMock = $this->createMock(Filter::class);

          $this->collectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
                                              ->disableOriginalConstructor()
                                              ->setMethods(['create'])
                                               ->getMock();

          $this->messageManagerMock = $this->createMock(ManagerInterface::class);

          $this->resultFactoryMock = $this->getMockBuilder(ResultFactory::class)
                                          ->disableOriginalConstructor()
                                          ->setMethods(['create'])
                                          ->getMock();

          $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
          $this->controllerMassDisable = $this->objectManager->getObject(
              MassDisable::class,
              [
                  'filter' => $this->filterMock,
                  'collectionFactory' => $this->collectionFactoryMock,
                  'messageManager' => $this->messageManagerMock,
                  'resultFactory' => $this->resultFactoryMock

              ]
          );
      }

      public function testExecute()
      {
          $size = 2;
          $collectionMock = $this->createMock(Collection::class);

          $this->collectionFactoryMock->method('create')
              ->willReturn($collectionMock);

          $this->filterMock->method('getCollection')
              ->willReturn($collectionMock);

          $commentModel = $this->createMock(\OpenTechiz\Blog\Model\Comment::class);
          $data = [
              0 => $commentModel,
              1 => $commentModel
          ];

          $collectionMock->method('getItems')
               ->willReturn($data);

          $collectionMock->method('getSize')
              ->willReturn($size);

          $commentModel->method('setIsActive')
              ->with(false)
              ->willReturnSelf();

          $commentModel->method('save')
               ->willReturnSelf();

          $this->messageManagerMock->method('addSuccess')
                            ->with(__('A total of %1 record(s) have been disabled.', $size))
                            ->willReturnSelf();

          $resultRedirect = $this->createMock(Redirect::class);

          $this->resultFactoryMock->method('create')
               ->with(ResultFactory::TYPE_REDIRECT)
               ->willReturn($resultRedirect);

          $resultRedirect->method('setPath')
              ->with('*/*/')
              ->willReturnSelf();

          $this->assertSame($resultRedirect, $this->controllerMassDisable->execute());


      }
}
