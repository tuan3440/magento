<?php


namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Post;

use PHPUnit\Framework\TestCase;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\Registry;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\RequestInterface;
use \Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\View\Page\Title;


class EditTest extends TestCase
{
    protected $controllerEditPost;
    protected $requestMock;
    protected $resultRedirectFactoryMock;
    protected $_objetcManagerMock;
    protected $messageManagerMock;
    protected $resultPageFactoryMock;
    protected $_coreRegisterMock;

    protected function setUp()
    {
        $this->requestMock = $this->createMock(RequestInterface::class);

        $this->resultRedirectFactoryMock = $this->createMock(RedirectFactory::class);

        $this->_objetcManagerMock = $this->createMock(ObjectManagerInterface::class);

        $this->messageManagerMock = $this->createMock(ManagerInterface::class);
        $this->resultPageFactoryMock = $this->createMock(PageFactory::class);
        $this->_coreRegisterMock = $this->createMock(Registry::class);

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->controllerEditPost = $objectManagerHelper->getObject(
            \OpenTechiz\Blog\Controller\Adminhtml\Post\Edit::class,
            [
                'request' => $this->requestMock,
                'resultRedirectFactory' => $this->resultRedirectFactoryMock,
                '_objectManager' => $this->_objetcManagerMock,
                'messageManager' => $this->messageManagerMock,
                'resultPageFactory' => $this->resultPageFactoryMock
            ]
        );
    }

    public function testPostNotExist()
    {
        $id = 1;
        $this->requestMock->method('getParam')->with('post_id')->willReturn($id);
        $redirect = $this->createMock(Redirect::class);
        $this->resultRedirectFactoryMock->method('create')->willReturn($redirect);
        $post = $this->createMock(\OpenTechiz\Blog\Model\Post::class);
        $this->_objetcManagerMock->method('create')->willReturn($post);
        $post->method('load')->with($id)->willReturnSelf();
        $post->method('getId')->willReturn(null);
        $this->messageManagerMock->method('addErrorMessage')->with(__('This post no longer exists.'))->willReturnSelf();
        $redirect->method('setPath')->with('*/*/')->willReturnSelf();

        $this->assertSame($redirect, $this->controllerEditPost->execute());
    }

//    public function testExecuteEditSuccess()
//    {
//        $id = 1;
//        $title = 'tuandz';
//        $this->requestMock->method('getParam')->with('post_id')->willReturn($id);
//        $redirect = $this->createMock(Redirect::class);
//        $this->resultRedirectFactoryMock->method('create')->willReturn($redirect);
//        $post = $this->createMock(\OpenTechiz\Blog\Model\Post::class);
//        $this->_objetcManagerMock->method('create')->willReturn($post);
//        $post->method('load')->with($id)->willReturnSelf();
//        $post->method('getId')->willReturn($id);
//        $post->method('getTitle')->willReturn($title);
//        $this->_coreRegisterMock->method('register')->with('blog_post', $post);
//
//        $resultPage = $this->createMock(Page::class);
//        $titleMock = $this->createMock(Title::class);
//        $this->resultPageFactoryMock->method('create')->willReturn($resultPage);
//        $resultPage->method('setActiveMenu')->with('OpenTechiz_Blog::post')->willReturnSelf();
//        $resultPage->expects($this->any())->method('addBreadcrumb')->with(__('Blog'), __('Blog'))->willReturnSelf();
//        $resultPage->expects($this->any())->method('addBreadcrumb')->with(__('Manage Blog Posts'), __('Manage Blog Posts'))->willReturnSelf();
//        $resultPage->expects($this->any())->method('addBreadcrumb')->with(__('Edit Blog Posts'), __('Edit Blog Posts'));
//        $pageConfig = $this->createMock(\Magento\Framework\View\Page\Config::class);
//        $resultPage->expects($this->at(0))->method('getConfig')->willReturn($pageConfig);
//        $pageConfig->method('getTitle')->willReturn($titleMock);
//        $titleMock->expects($this->at(0))->method('prepend')->with(__('Blog Posts'));
//
//        $resultPage->expects($this->at(1))->method('getConfig')->willReturn($pageConfig);
//        $titleMock->expects($this->at(1))->method('prepend')->with($title);
//        $this->assertSame($resultPage, $this->controllerEditPost->execute());
//    }

}
