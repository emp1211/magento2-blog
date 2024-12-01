<?php

declare(strict_types=1);

namespace BlueHeron\Blog\ViewModel;

use BlueHeron\Blog\Model\ResourceModel\Post\Collection;
use BlueHeron\Blog\Service\PostsProvider;
use BlueHeron\Blog\Model\Post;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;
use Magento\Theme\Block\Html\Pager;

class Posts implements ArgumentInterface
{
    public function __construct(
        private PostsProvider $postsProvider,
        private RequestInterface $request
    ) {}

    public function getPosts(int $limit): Collection
    {
        return $this->postsProvider->getPosts($limit, $this->getCurrentPage());
    }

    private function getCurrentPage(): int 
    {
        return (int) $this->request->getParam('p');
    }

    public function getPager(Collection $collection, Pager $pagerBlock): string
    {
        $pagerBlock->setUseContainer(false)
            ->setShowPerPage(false)
            ->setShowAmount(false)
            ->setFrameLength(3)
            ->setLimit($collection->getPageSize())
            ->setCollection($collection);
        
        return $pagerBlock->toHtml();
    }

    public function getPostHtml(Template $block, Post $post): string 
    {
        $block->setData('post', $post);
        return $block->toHtml();
    }
}