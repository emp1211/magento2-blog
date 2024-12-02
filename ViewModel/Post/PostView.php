<?php

declare(strict_types=1);

namespace BlueHeron\Blog\ViewModel\Post;

use BlueHeron\Blog\Model\Post;
use BlueHeron\Blog\Model\ResourceModel\Post\Collection;
use BlueHeron\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class PostView implements ArgumentInterface
{
    public function __construct(
        private RequestInterface $request,
        private CollectionFactory $collectionFactory
    ) {}

    public function getPost(): Post
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('post_id', (int)$this->request->getParam('post_id'));
        return $collection->getFirstItem();
    }
}