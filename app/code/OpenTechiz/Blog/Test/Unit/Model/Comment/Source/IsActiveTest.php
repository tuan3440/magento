<?php


namespace OpenTechiz\Blog\Test\Unit\Model\Comment\Source;
use PHPUnit\Framework\TestCase;
use OpenTechiz\Blog\Model\Comment;
use OpenTechiz\Blog\Model\Comment\Source\IsActive;
use Magento\Framework\Data\OptionSourceInterface;
class IsActiveTest extends TestCase
{
   public $comment;
//   protected function setUp()
//   {
//          $this->comment = $this->createMock(Comment::class);
//   }

//    public function testToOptionArrayReturnExact()
//    {
//        $availableOptions = [0 => 'Disable', 1 => 'Enable', 2 => 'Pending'];
//
//        $this->comment->method('getAvailableStatuses')->willReturn($availableOptions);
//        $options = [
//            [
//                'label' => 'Disable',
//                'value' => 0
//            ],
//            [
//                'label' => 'Enable',
//                'value' => 1
//            ],
//            [
//                'label' => 'Pending',
//                'value' => 2
//            ]
//        ];
//
//
//        $isActive = new IsActive($this->comment);
//
//        $this->assertEquals($options, $isActive->toOptionArray());
//
//    }
    protected $isActive;
    protected function setUp()
    {
        $this->comment = $this->createMock(Comment::class);

        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->isActive = $this->objectManager->getObject(
            IsActive::class,
            [
                'comment' => $this->comment

            ]
        );
    }

    public function testToOptionArrayReturnExact()
    {
        $availableOptions = [0 => 'Disable', 1 => 'Enable', 2 => 'Pending'];

        $this->comment->method('getAvailableStatuses')->willReturn($availableOptions);
        $options = [
            [
                'label' => 'Disable',
                'value' => 0
            ],
            [
                'label' => 'Enable',
                'value' => 1
            ],
            [
                'label' => 'Pending',
                'value' => 2
            ]
        ];




        $this->assertEquals($options, $this->isActive->toOptionArray());

    }

}


