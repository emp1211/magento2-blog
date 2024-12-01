<?php 

declare (strict_types=1);

namespace BlueHeron\Blog\ViewModel;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use BlueHeron\Blog\Model\Post;

class PostViewModel implements ArgumentInterface
{
    public function __construct(private UrlInterface $url) {}

    public function getPostUrl(Post $post): string 
    {
        return $this->url->getBaseUrl() . 'blog/' . $post->getData('url_key');
    }
}