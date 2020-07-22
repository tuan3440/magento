<?php

namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Comment;

use ArrayIterator;
use PHPUnit\Framework\TestCase;
use OpenTechiz\Blog\Controller\Adminhtml\Comment\MassDelete;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\ObjectManager\ObjectManager;
use Magento\Ui\Component\MassAction\Filter;
use OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory;
use OpenTechiz\Blog\Model\ResourceModel\Comment\Collection;
use PHPUnit_Framework_MockObject_MockObject;
use OpenTechiz\Blog\Model\Comment;


class MassDeleteTest extends TestCase
{
    protected $controllerMassDelete;
    protected $filterMock;
    protected $collectionFactoryMock;
    protected $commentCollectionMock;
    protected $messageManagerMock;
    protected $resultFactoryMock;
    protected $resultRedirectMock;


    protected function setUp()
    {
        $this->filterMock = $this->createMock(Filter::class);

        $this->collectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
                                            ->disableOriginalConstructor()
                                            ->setMethods(['create'])
                                            ->getMock();

        $this->commentCollectionMock = $this->createMock(Collection::class);
        $this->collectionFactoryMock->expects($this->atLeastOnce())
            ->method('create')->willReturn($this->commentCollectionMock);

        $this->messageManagerMock = $this->getMockBuilder(ManagerInterface::class)
                                         ->disableOriginalConstructor()
                                         ->setMethods(['addSuccessMessage'])
                                         ->getMockForAbstractClass();

        $this->resultFactoryMock = $this->getMockBuilder(ResultFactory::class)
                                        ->disableOriginalConstructor()
                                        ->setMethods(['create'])
                                        ->getMock();




        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->controllerMassDelete = $this->objectManager->getObject(
            MassDelete::class,
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
        $size = 2 ;
        $this->filterMock->expects($this->once())
            ->method('getCollection')
            ->willReturn($this->commentCollectionMock);

        $this->commentCollectionMock->expects($this->once())
            ->method('getSize')->willReturn(2);

        $commentModel = $this->createMock(\OpenTechiz\Blog\Model\Comment::class);
        $data = [
            0 => $commentModel,
            1 => $commentModel
        ];
        $this->commentCollectionMock->expects($this->once())
            ->method('getItems')->willReturn($data);

        $commentModel->expects($this->any())->method('delete')->willReturnSelf();

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

        $this->assertSame($resultRedirect, $this->controllerMassDelete->execute());
    }
}

















