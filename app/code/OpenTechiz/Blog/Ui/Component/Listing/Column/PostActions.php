<?php

namespace OpenTechiz\Blog\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Escaper;

class PostActions extends Column
{
    /** Url part */
    const BLOG_URL_PATH_EDIT = 'blog/post/edit';
    const BLOG_URL_PATH_DELETE = 'blog/post/delete';

    /** @var UrlInterface */
    protected $urlBuilder;

    /** @var string */
    private $editUrl;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @param ContexInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::BLOG_URL_PATH_EDIT
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['post_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['post_id' => $item['post_id']]),
                        'label' => __('Edit')
                    ];
                    $title = $this->getEscaper()->escapeHtml($item['title']);
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::BLOG_URL_PATH_DELETE, ['post_id' => $item['post_id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete %1', $title),
                            'message' => __('Are you sure you want to delete a %1 record?', $title)
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }

    /**
     * Get instance of escaper
     *
     * @return Escaper
     * @deprecated 101.0.7
     */
    private function getEscaper()
    {
        if (!$this->escaper) {
            $this->escaper = ObjectManager::getInstance()->get(Escaper::class);
        }
        return $this->escaper;
    }
}