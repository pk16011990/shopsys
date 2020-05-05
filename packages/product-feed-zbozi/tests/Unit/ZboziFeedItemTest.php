<?php

namespace Tests\ProductFeed\ZboziBundle\Unit;

use PHPUnit\Framework\TestCase;
use Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Model\Category\CategoryFacade;
use Shopsys\FrameworkBundle\Model\Pricing\Price;
use Shopsys\FrameworkBundle\Model\Product\Availability\Availability;
use Shopsys\FrameworkBundle\Model\Product\Brand\Brand;
use Shopsys\FrameworkBundle\Model\Product\Collection\ProductParametersBatchLoader;
use Shopsys\FrameworkBundle\Model\Product\Collection\ProductUrlsBatchLoader;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceCalculationForCustomerUser;
use Shopsys\FrameworkBundle\Model\Product\Product;
use Shopsys\ProductFeed\ZboziBundle\Model\FeedItem\ZboziFeedItem;
use Shopsys\ProductFeed\ZboziBundle\Model\FeedItem\ZboziFeedItemFactory;
use Shopsys\ProductFeed\ZboziBundle\Model\Product\ZboziProductDomain;
use Shopsys\ProductFeed\ZboziBundle\Model\Product\ZboziProductDomainData;
use Tests\FrameworkBundle\Test\IsMoneyEqual;

class ZboziFeedItemTest extends TestCase
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceCalculationForCustomerUser|\PHPUnit\Framework\MockObject\MockObject
     */
    private $productPriceCalculationForCustomerUserMock;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Collection\ProductUrlsBatchLoader|\PHPUnit\Framework\MockObject\MockObject
     */
    private $productUrlsBatchLoaderMock;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Collection\ProductParametersBatchLoader|\PHPUnit\Framework\MockObject\MockObject
     */
    private $productParametersBatchLoaderMock;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Category\CategoryFacade|\PHPUnit\Framework\MockObject\MockObject
     */
    private $categoryFacadeMock;

    /**
     * @var \Shopsys\ProductFeed\ZboziBundle\Model\FeedItem\ZboziFeedItemFactory
     */
    private $zboziFeedItemFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig
     */
    private $defaultDomain;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Product|\PHPUnit\Framework\MockObject\MockObject
     */
    private $defaultProduct;

    protected function setUp(): void
    {
        $this->productPriceCalculationForCustomerUserMock = $this->createMock(ProductPriceCalculationForCustomerUser::class);
        $this->productUrlsBatchLoaderMock = $this->createMock(ProductUrlsBatchLoader::class);
        $this->productParametersBatchLoaderMock = $this->createMock(ProductParametersBatchLoader::class);
        $this->categoryFacadeMock = $this->createMock(CategoryFacade::class);

        $this->zboziFeedItemFactory = new ZboziFeedItemFactory(
            $this->productPriceCalculationForCustomerUserMock,
            $this->productUrlsBatchLoaderMock,
            $this->productParametersBatchLoaderMock,
            $this->categoryFacadeMock
        );

        $this->defaultDomain = $this->createDomainConfigMock(Domain::FIRST_DOMAIN_ID, 'https://example.cz', 'cs');

        $this->defaultProduct = $this->createMock(Product::class);
        $this->defaultProduct->method('getId')->willReturn(1);
        $this->defaultProduct->method('getName')->with('cs')->willReturn('product name');

        $availabilityMock = $this->createMock(Availability::class);
        /* @var \Shopsys\FrameworkBundle\Model\Product\Availability\Availability|\PHPUnit\Framework\MockObject\MockObject $availabilityMock */
        $availabilityMock->method('getDispatchTime')->willReturn(0);
        $this->defaultProduct->method('getCalculatedAvailability')->willReturn($availabilityMock);

        $productPrice = new ProductPrice(Price::zero(), false);
        $this->productPriceCalculationForCustomerUserMock->method('calculatePriceForCustomerUserAndDomainId')
            ->with($this->defaultProduct, Domain::FIRST_DOMAIN_ID, null)->willReturn($productPrice);

        $this->productUrlsBatchLoaderMock->method('getProductUrl')
            ->with($this->defaultProduct, $this->defaultDomain)->willReturn('https://example.com/product-1');

        $this->categoryFacadeMock->method('getCategoryNamesInPathFromRootToProductMainCategoryOnDomain')
            ->with($this->defaultProduct, $this->defaultDomain)->willReturn(['category A', 'category B', 'category C']);
    }

    /**
     * @param int $id
     * @param string $url
     * @param string $locale
     * @return \Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig
     */
    private function createDomainConfigMock(int $id, string $url, string $locale): DomainConfig
    {
        $domainConfigMock = $this->createMock(DomainConfig::class);

        $domainConfigMock->method('getId')->willReturn($id);
        $domainConfigMock->method('getUrl')->willReturn($url);
        $domainConfigMock->method('getLocale')->willReturn($locale);

        return $domainConfigMock;
    }

    public function testMinimalZboziFeedItemIsCreatable()
    {
        $zboziFeedItem = $this->zboziFeedItemFactory->create($this->defaultProduct, null, $this->defaultDomain);

        self::assertInstanceOf(ZboziFeedItem::class, $zboziFeedItem);

        self::assertEquals(1, $zboziFeedItem->getId());
        self::assertEquals(1, $zboziFeedItem->getSeekId());
        self::assertNull($zboziFeedItem->getGroupId());
        self::assertEquals('product name', $zboziFeedItem->getName());
        self::assertNull($zboziFeedItem->getDescription());
        self::assertEquals('https://example.com/product-1', $zboziFeedItem->getUrl());
        self::assertNull($zboziFeedItem->getImgUrl());
        self::assertThat($zboziFeedItem->getPrice()->getPriceWithoutVat(), new IsMoneyEqual(Money::zero()));
        self::assertThat($zboziFeedItem->getPrice()->getPriceWithVat(), new IsMoneyEqual(Money::zero()));
        self::assertNull($zboziFeedItem->getEan());
        self::assertNull($zboziFeedItem->getProductno());
        self::assertEquals(0, $zboziFeedItem->getDeliveryDate());
        self::assertNull($zboziFeedItem->getManufacturer());
        self::assertEquals('category A | category B | category C', $zboziFeedItem->getCategoryText());
        self::assertEquals([], $zboziFeedItem->getParams());
        self::assertNull($zboziFeedItem->getMaxCpc());
        self::assertNull($zboziFeedItem->getMaxCpcSearch());
    }

    public function testZboziFeedItemWithGroupId()
    {
        $mainVariantMock = $this->createMock(Product::class);
        $mainVariantMock->method('getId')->willReturn(2);
        $this->defaultProduct->method('isVariant')->willReturn(true);
        $this->defaultProduct->method('getMainVariant')->willReturn($mainVariantMock);

        $zboziFeedItem = $this->zboziFeedItemFactory->create($this->defaultProduct, null, $this->defaultDomain);

        self::assertEquals(2, $zboziFeedItem->getGroupId());
    }

    public function testZboziFeedItemWithDescription()
    {
        $this->defaultProduct->method('getDescription')
            ->with(1)->willReturn('product description');

        $zboziFeedItem = $this->zboziFeedItemFactory->create($this->defaultProduct, null, $this->defaultDomain);

        self::assertEquals('product description', $zboziFeedItem->getDescription());
    }

    public function testZboziFeedItemWithImgUrl()
    {
        $this->productUrlsBatchLoaderMock->method('getProductImageUrl')
            ->with($this->defaultProduct, $this->defaultDomain)->willReturn('https://example.com/img/product/1');

        $zboziFeedItem = $this->zboziFeedItemFactory->create($this->defaultProduct, null, $this->defaultDomain);

        self::assertEquals('https://example.com/img/product/1', $zboziFeedItem->getImgUrl());
    }

    public function testZboziFeedItemWithEan()
    {
        $this->defaultProduct->method('getEan')->willReturn('1234567890123');

        $zboziFeedItem = $this->zboziFeedItemFactory->create($this->defaultProduct, null, $this->defaultDomain);

        self::assertEquals('1234567890123', $zboziFeedItem->getEan());
    }

    public function testZboziFeedItemWithProductno()
    {
        $this->defaultProduct->method('getPartno')->willReturn('PN01-B');

        $zboziFeedItem = $this->zboziFeedItemFactory->create($this->defaultProduct, null, $this->defaultDomain);

        self::assertEquals('PN01-B', $zboziFeedItem->getProductno());
    }

    public function testZboziFeedItemWithManufacturer()
    {
        $brand = $this->createMock(Brand::class);
        /* @var \Shopsys\FrameworkBundle\Model\Product\Brand\Brand|\PHPUnit\Framework\MockObject\MockObject $brand */
        $brand->method('getName')->willReturn('manufacturer name');
        $this->defaultProduct->method('getBrand')->willReturn($brand);

        $zboziFeedItem = $this->zboziFeedItemFactory->create($this->defaultProduct, null, $this->defaultDomain);

        self::assertEquals('manufacturer name', $zboziFeedItem->getManufacturer());
    }

    public function testZboziFeedItemWithParams()
    {
        $this->productParametersBatchLoaderMock->method('getProductParametersByName')
            ->with($this->defaultProduct, $this->defaultDomain)->willReturn(['color' => 'black']);

        $zboziFeedItem = $this->zboziFeedItemFactory->create($this->defaultProduct, null, $this->defaultDomain);

        self::assertEquals(['color' => 'black'], $zboziFeedItem->getParams());
    }

    public function testZboziFeedItemWithMaxCpc()
    {
        $zboziProductDomainData = new ZboziProductDomainData();
        $zboziProductDomainData->cpc = Money::create('5.0');
        $zboziProductDomain = new ZboziProductDomain($zboziProductDomainData);

        $zboziFeedItem = $this->zboziFeedItemFactory->create($this->defaultProduct, $zboziProductDomain, $this->defaultDomain);

        self::assertThat($zboziFeedItem->getMaxCpc(), new IsMoneyEqual(Money::create(5)));
        self::assertNull($zboziFeedItem->getMaxCpcSearch());
    }

    public function testZboziFeedItemWithMaxCpcSearch()
    {
        $zboziProductDomainData = new ZboziProductDomainData();
        $zboziProductDomainData->cpcSearch = Money::create('5.0');
        $zboziProductDomain = new ZboziProductDomain($zboziProductDomainData);

        $zboziFeedItem = $this->zboziFeedItemFactory->create($this->defaultProduct, $zboziProductDomain, $this->defaultDomain);

        self::assertNull($zboziFeedItem->getMaxCpc());
        self::assertThat($zboziFeedItem->getMaxCpcSearch(), new IsMoneyEqual(Money::create(5)));
    }
}
