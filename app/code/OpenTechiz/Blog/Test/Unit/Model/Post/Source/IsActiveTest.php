<?php


namespace OpenTechiz\Blog\Test\Unit\Model\Post\Source;
use PHPUnit\Framework\TestCase;
use OpenTechiz\Blog\Model\Post;
use OpenTechiz\Blog\Model\Post\Source\IsActive;
use Magento\Framework\Data\OptionSourceInterface;
class IsActiveTest extends TestCase
{
    public $post;
    protected function setUp()
    {
        $this->post = $this->createMock(Post::class);
    }

    public function testToOptionArrayReturnExact()
    {
        $availableOptions = [0 => 'Disable', 1 => 'Enable'];

        $this->post->method('getAvailableStatuses')->willReturn($availableOptions);
        $options = [
            [
                'label' => 'Disable',
                'value' => 0
            ],
            [
                'label' => 'Enable',
                'value' => 1
            ]

        ];


        $isActive = new IsActive($this->post);

        $this->assertEquals($options, $isActive->toOptionArray());

    }

}


