<?php

declare(strict_types=1);

namespace BlueHeron\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use BlueHeron\Blog\Model\ResourceModel\Post as PostResource;
use BlueHeron\Blog\Model\PostFactory;

class Delete extends Action implements HttpPostActionInterface
{
    public function __construct(
        Context $context,
        private PostResource $resource,
        private PostFactory $postFactory
    )
    {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        // Get the post_id from the request params
        $postId = (int) $this->getRequest()->getParam('post_id');

        // Create the result redirect (inherited from Action class)
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$postId) {
            $this->messageManager->addErrorMessage(__('We can\'t find a post to delete'));
            return $resultRedirect->setPath('*/*/');
        }

        $model = $this->postFactory->create();
        
        try {
            $this->resource->load($model, $postId);

            // Delete record from db table
            $this->resource->delete($model);

            $this->messageManager->addSuccessMessage(__('Post successfully deleted.'));
        } catch (\Throwable $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}