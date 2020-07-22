<?php


namespace OpenTechiz\Blog\Test\Unit\Ui\Component\Listing\Column;

use OpenTechiz\Blog\Ui\Component\Listing\Column\PostActions;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test For OpenTechiz\Blog\Ui\Component\Listing\Column\PostActions Class
 */
class PostActionsTest extends TestCase
{
    protected $urlBuilderMock;
    protected $editUrlMock;
    protected $model;

    protected function setUp()
    {
        $this->urlBuilderMock = $this->createMock(UrlInterface::class);

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject(
            \OpenTechiz\Blog\Ui\Component\Listing\Column\PostActions::class,
            [
                'urlBuilder' => $this->urlBuilderMock


            ]
        );
    }

    public function testPrepare()
    {
        $postID = 1;
        $title = 'tuandz';
        $name = 'item_name';
       $dataSource = [
           'data' => [
                'items' => [
                   [
                       'post_id' => $postID,
                       'title' => $title
                   ]
              ]
           ]
       ];

       $dataExpect = [
           [
               'post_id' => $postID,
              'title' => $title,
               $name => [
                   'edit' => [
                       'href' => 'test/url/edit',
                       'label' => __('Edit'),
                   ],
                    'delete' => [
                        'href' => 'test/url/delete',
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete %1', $title),
                            'message' => __('Are you sure you want to delete a %1 record?', $title),
                        ]
                    ],
                ],
            ],
       ];

       $this->model->setName($name);

       $this->urlBuilderMock->method('getUrl')
           ->willReturnMap(
                [
                    [
                        PostActions::BLOG_URL_PATH_EDIT,
                        [
                            'post_id' => $postID
                        ],
                        'test/url/edit',
                    ],
                    [
                        PostActions::BLOG_URL_PATH_DELETE,
                        [
                            'post_id' => $postID
                        ],
                        'test/url/delete',
                    ],
                ]
            );

       $this->assertSame($dataExpect, $this->model->prepareDataSource($dataSource)['data']['items']);


    }

//    public function testPrepareItemsByPostId()
//    {
//        $postID = 1;
//        // Create Mocks and SUT
//        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
//
//        /** @var \PHPUnit_Framework_MockObject_MockObject $urlBuilderMock */
//        $urlBuilderMock = $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
//            ->disableOriginalConstructor()
//            ->getMock();
//
//        $contextMock = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\ContextInterface::class)
//            ->getMockForAbstractClass();
//
//        $processor = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\Processor::class)
//            ->disableOriginalConstructor()
//            ->getMock();
//        $contextMock->expects($this->never())->method('getProcessor')->willReturn($processor);
//
//        /** @var OpenTechiz\Blog\Ui\Component\Listing\Column\PostActions $model */
//        $model = $objectManager->getObject(
//            \OpenTechiz\Blog\Ui\Component\Listing\Column\PostActions::class,
//            [
//                'urlBuilder' => $urlBuilderMock,
//                'context' => $contextMock,
//            ]
//        );
//
//        $escaper = $this->getMockBuilder(Escaper::class)
//            ->disableOriginalConstructor()
//            ->setMethods(['escapeHtml'])
//            ->getMock();
//        $objectManager->setBackwardCompatibleProperty($model, 'escaper', $escaper);
//
//        // Define test input and expectations
//        $title = 'post title';
//        $items = [
//            'data' => [
//                'items' => [
//                    [
//                        'post_id' => $postID,
//                        'title' => $title
//                    ]
//                ]
//            ]
//        ];
//        $name = 'item_name';
//        $expectedItems = [
//            [
//                'post_id' => $postID,
//                'title' => $title,
//                $name => [
//                    'edit' => [
//                        'href' => 'test/url/edit',
//                        'label' => __('Edit'),
//                    ],
//                    'delete' => [
//                        'href' => 'test/url/delete',
//                        'label' => __('Delete'),
//                        'confirm' => [
//                            'title' => __('Delete %1', $title),
//                            'message' => __('Are you sure you want to delete a %1 record?', $title),
//                        ]
//                    ],
//                ],
//            ],
//        ];
//
//        $escaper->expects(static::once())
//            ->method('escapeHtml')
//            ->with($title)
//            ->willReturn($title);
//
//        // Configure mocks and object data
//        $urlBuilderMock->expects($this->any())
//            ->method('getUrl')
//            ->willReturnMap(
//                [
//                    [
//                        PostActions::BLOG_URL_PATH_EDIT,
//                        [
//                            'post_id' => $postID
//                        ],
//                        'test/url/edit',
//                    ],
//                    [
//                        PostActions::BLOG_URL_PATH_DELETE,
//                        [
//                            'post_id' => $postID
//                        ],
//                        'test/url/delete',
//                    ],
//                ]
//            );
//        $model->setName($name);
//        $items = $model->prepareDataSource($items);
//        // Run test
//        $this->assertEquals($expectedItems, $items['data']['items']);
//    }
//

}







