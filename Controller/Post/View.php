<?php

declare(strict_types=1);

namespace BlueHeron\Blog\Controller\Post;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

class View implements HttpGetActionInterface
{
    public function __construct(
        private PageFactory $pageFactory,
        private RequestInterface $request
    ) {}

    public function execute()
    {
        return $this->pageFactory->create();
    }
}