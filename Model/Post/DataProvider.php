<?php

declare(strict_types=1);

namespace BlueHeron\Blog\Model\Post;

use BlueHeron\Blog\Model\Post;
use BlueHeron\Blog\Model\PostFactory;
use BlueHeron\Blog\Model\ResourceModel\Post as PostResource;
use BlueHeron\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

/**
 * Data provider class for the blog post entity.
 */
class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var array Loaded data for the blog posts.
     */
    private array $loadedData;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param PostResource $resource
     * @param PostFactory $postFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        private PostResource $resource,
        private PostFactory $postFactory,
        private RequestInterface $request,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->collection = $collectionFactory->create();
    }

    /**
     * Retrieves data for the blog post entity.
     *
     * @return array
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $post = $this->getCurrentPost();
        $this->loadedData[$post->getId()] = $post->getData();

        return $this->loadedData;
    }

    /**
     * Retrieves the current blog post based on the request parameter.
     *
     * @return Post
     */
    private function getCurrentPost(): Post
    {
        $postId = $this->getPostId();
        $post = $this->postFactory->create();
        if (!$postId) {
            return $post;
        }

        $this->resource->load($post, $postId);

        return $post;
    }

    /**
     * Retrieves the post ID from the request parameters.
     *
     * @return int
     */
    private function getPostId(): int
    {
        return (int) $this->request->getParam($this->getRequestFieldName());
    }
}
