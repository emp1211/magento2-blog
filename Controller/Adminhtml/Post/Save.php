<?php 

declare(strict_types=1);

namespace BlueHeron\Blog\Controller\Adminhtml\Post;

use BlueHeron\Blog\Model\PostFactory;
use BlueHeron\Blog\Model\ResourceModel\Post as PostResource;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;

class Save extends Action implements HttpPostActionInterface
{
    public function __construct(
        Context $context,
        private PostFactory $postFactory,
        private PostResource $resource
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            // Create instance of Post Model class
            $model = $this->postFactory->create();

            // If this is a new entry it will not have a post_id, therefore set to null so a new record is created
            if (empty($data['post_id'])) {
                $data['post_id'] = null;
            }

            // Save data entered to Post Model object
            $model->setData($data);

            try {
                // Use Post ResourceModel to write the record object to the db table
                $this->resource->save($model);

                // From parent Action class provide success message
                $this->messageManager->addSuccessMessage(__('Post successfully saved.')); 

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $exception) {
                $this->messageManager->addExceptionMessage($exception);
            } catch (\Throwable $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the post.'));
            }
        }

        // If Post req contains no data then redirect admin user to manage posts page
        return $resultRedirect->setPath('*/*/');
    }
}