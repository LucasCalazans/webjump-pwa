<?php

declare(strict_types=1);

namespace Webjump\PWA\Model\Resolver;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;

/**
 * @inheritdoc
 *
 * Returns available CMS page names
 */
class CustomCMSPages implements ResolverInterface
{

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * Update store view plugin constructor
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        PageRepositoryInterface $pageRepository
    )
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        $pages = [];
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $pageCollection = $this->pageRepository->getList($searchCriteria)->getItems();

        foreach ($pageCollection as $page) {
            $pages[] = array(
                "title" => $page->getTitle(),
                "url_path" => $page->getIdentifier(),
                "content_heading" => $page->getContentHeading(),
                "content" => $page->getContent(),
                "meta_title" => $page->getMetaTitle(),
                "meta_keywords" => $page->getMetaKeywords(),
                "meta_description" => $page->getMetaDescription(),
                "store_id" => $page->getStoreId(),
                "custom_theme_from" => $page->getCustomThemeFrom(),
                "custom_theme_to" => $page->getCustomThemeTo(),
            );
        }

        return $pages;
    }
}
