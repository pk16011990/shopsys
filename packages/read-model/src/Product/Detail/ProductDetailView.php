<?php

declare(strict_types=1);

namespace Shopsys\ReadModelBundle\Product\Detail;

use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice;
use Shopsys\ReadModelBundle\Brand\BrandView;
use Shopsys\ReadModelBundle\Image\ImageView;
use Shopsys\ReadModelBundle\Product\Action\ProductActionView;

class ProductDetailView
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int[]
     */
    protected $flagIds;

    /**
     * @var \Shopsys\ReadModelBundle\Image\ImageView|null
     */
    protected $mainImageView;

    /**
     * @var \Shopsys\ReadModelBundle\Product\Action\ProductActionView
     */
    protected $actionView;

    /**
     * @var string
     */
    protected $seoPageTitle;

    /**
     * @var string
     */
    protected $availability;

    /**
     * @var bool
     */
    protected $isInStock;

    /**
     * @var bool
     */
    protected $isSellingDenied;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice|null
     */
    protected $sellingPrice;

    /**
     * @var int
     */
    protected $mainCategoryId;

    /**
     * @var \Shopsys\ReadModelBundle\Brand\BrandView|null
     */
    protected $brandView;

    /**
     * @var string
     */
    protected $catnum;

    /**
     * @var string
     */
    protected $partno;

    /**
     * @var string
     */
    protected $ean;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \Shopsys\ReadModelBundle\Image\ImageView[]
     */
    protected $galleryImageViews;

    /**
     * @var string
     */
    protected $seoMetaDescription;

    /**
     * @var bool
     */
    protected $isMainVariant;

    /**
     * @var int|null
     */
    protected $mainVariantId;

    /**
     * @var array
     */
    protected $parameterViews;

    /**
     * @param int $id
     * @param string|null $name
     * @param string|null $description
     * @param string $availability
     * @param \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice|null $sellingPrice
     * @param string|null $catnum
     * @param string|null $partno
     * @param string|null $ean
     * @param int $mainCategoryId
     * @param bool $isSellingDenied
     * @param bool $isInStock
     * @param bool $isMainVariant
     * @param int|null $mainVariantId
     * @param int[] $flagIds
     * @param string|null $seoPageTitle
     * @param string|null $seoMetaDescription
     * @param \Shopsys\ReadModelBundle\Product\Action\ProductActionView $actionView
     * @param \Shopsys\ReadModelBundle\Brand\BrandView|null $brandView
     * @param \Shopsys\ReadModelBundle\Image\ImageView|null $mainImageView
     * @param \Shopsys\ReadModelBundle\Image\ImageView[] $galleryImageViews
     * @param \Shopsys\ReadModelBundle\Parameter\ParameterView[] $parameterViews
     */
    public function __construct(
        int $id,
        ?string $name,
        ?string $description,
        string $availability,
        ?ProductPrice $sellingPrice,
        ?string $catnum,
        ?string $partno,
        ?string $ean,
        int $mainCategoryId,
        bool $isSellingDenied,
        bool $isInStock,
        bool $isMainVariant,
        ?int $mainVariantId,
        array $flagIds,
        ?string $seoPageTitle,
        ?string $seoMetaDescription,
        ProductActionView $actionView,
        ?BrandView $brandView,
        ?ImageView $mainImageView,
        array $galleryImageViews,
        array $parameterViews
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->availability = $availability;
        $this->sellingPrice = $sellingPrice;
        $this->catnum = $catnum;
        $this->partno = $partno;
        $this->ean = $ean;
        $this->mainCategoryId = $mainCategoryId;
        $this->isSellingDenied = $isSellingDenied;
        $this->isInStock = $isInStock;
        $this->isMainVariant = $isMainVariant;
        $this->mainVariantId = $mainVariantId;
        $this->flagIds = $flagIds;
        $this->seoPageTitle = $seoPageTitle;
        $this->seoMetaDescription = $seoMetaDescription;
        $this->actionView = $actionView;
        $this->mainImageView = $mainImageView;
        $this->brandView = $brandView;
        $this->galleryImageViews = $galleryImageViews;
        $this->parameterViews = $parameterViews;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getAvailability(): string
    {
        return $this->availability;
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice|null
     */
    public function getSellingPrice(): ?ProductPrice
    {
        return $this->sellingPrice;
    }

    /**
     * @return string|null
     */
    public function getCatnum(): ?string
    {
        return $this->catnum;
    }

    /**
     * @return string|null
     */
    public function getPartno(): ?string
    {
        return $this->partno;
    }

    /**
     * @return string|null
     */
    public function getEan(): ?string
    {
        return $this->ean;
    }

    /**
     * @return int
     */
    public function getMainCategoryId(): int
    {
        return $this->mainCategoryId;
    }

    /**
     * @return bool
     */
    public function isIsSellingDenied(): bool
    {
        return $this->isSellingDenied;
    }

    /**
     * @return bool
     */
    public function isInStock(): bool
    {
        return $this->isInStock;
    }

    /**
     * @return bool
     */
    public function isMainVariant(): bool
    {
        return $this->isMainVariant;
    }

    /**
     * @return bool
     */
    public function isVariant(): bool
    {
        return $this->mainVariantId !== null;
    }

    /**
     * @return int|null
     */
    public function getMainVariantId(): ?int
    {
        return $this->mainVariantId;
    }

    /**
     * @return int[]
     */
    public function getFlagIds(): array
    {
        return $this->flagIds;
    }

    /**
     * @return string|null
     */
    public function getSeoPageTitle(): ?string
    {
        return $this->seoPageTitle;
    }

    /**
     * @return string|null
     */
    public function getSeoMetaDescription(): ?string
    {
        return $this->seoMetaDescription;
    }

    /**
     * @return \Shopsys\ReadModelBundle\Product\Action\ProductActionView
     */
    public function getActionView(): ProductActionView
    {
        return $this->actionView;
    }

    /**
     * @return \Shopsys\ReadModelBundle\Image\ImageView|null
     */
    public function getMainImageView(): ?ImageView
    {
        return $this->mainImageView;
    }

    /**
     * @return \Shopsys\ReadModelBundle\Brand\BrandView|null
     */
    public function getBrandView(): ?BrandView
    {
        return $this->brandView;
    }

    /**
     * @return \Shopsys\ReadModelBundle\Image\ImageView[]
     */
    public function getGalleryImageViews(): array
    {
        return $this->galleryImageViews;
    }

    /**
     * @return \Shopsys\ReadModelBundle\Parameter\ParameterView[]
     */
    public function getParameterViews(): array
    {
        return $this->parameterViews;
    }
}
