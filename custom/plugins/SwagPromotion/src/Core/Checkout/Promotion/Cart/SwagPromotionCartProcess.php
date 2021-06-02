<?php

namespace SwagPromotion\Core\Checkout\Promotion\Cart;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Checkout\Cart\Price\PercentagePriceCalculator;
use Shopware\Core\Checkout\Cart\Price\QuantityPriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\PercentagePriceDefinition;
use Shopware\Core\Checkout\Cart\Price\Struct\PriceCollection;
use Shopware\Core\Checkout\Cart\Rule\LineItemRule;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class SwagPromotionCartProcess implements CartProcessorInterface, CartDataCollectorInterface
{
    public const TYPE = 'product';
    public const DISCOUNT_TYPE = 'swagpromotion-discount';
    public const DATA_KEY = 'swag_promotion-';

    /**
     * @var EntityRepositoryInterface
     */
    private $promotionRepository;

    /**
     * @var PercentagePriceCalculator
     */
    private $percentagePriceCalculator;

    /**
     * @var QuantityPriceCalculator
     */
    private $quantityPriceCalculator;

    public function __construct(EntityRepositoryInterface $promotionRepository,
                                PercentagePriceCalculator $percentagePriceCalculator,
                                QuantityPriceCalculator $quantityPriceCalculator
    )
    {
        $this->promotionRepository = $promotionRepository;
        $this->percentagePriceCalculator = $percentagePriceCalculator;
        $this->quantityPriceCalculator = $quantityPriceCalculator;
    }

    public function collect(CartDataCollection $data, Cart $original, SalesChannelContext $context, CartBehavior $behavior): void
    {
        /** @var LineItemCollection $productLineItems */
        $productLineItems = $original->getLineItems()->filterType(self::TYPE);
        // no product in cart? exit
        if (\count($productLineItems) === 0) {
            return;
        }

        foreach ($productLineItems as $productLineItem) {
            $promotions = $this->getPromotions($context->getContext(), $productLineItem->getReferencedId());

            //add discount lineItem to product lineItem
            $this->addPromotionToLineItem($productLineItem, $promotions);

            $this->addDiscount($productLineItems, $productLineItem, $promotions, $context);
        }
    }

    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {
        /** @var LineItemCollection $productLineItems */
        $productLineItems = $original->getLineItems()->filterType(self::TYPE);

//        $this->calculatorProduct($productLineItems, $context);
        foreach ($productLineItems as $productLineItem) {
            $promotions = $this->getPromotions($context->getContext(), $productLineItem->getReferencedId());
            $this->calculatorDiscount($productLineItem, $promotions, $context);


            $productLineItem->setPrice(
                (new PriceCollection([$productLineItem->getPrice(), $productLineItem->getChildren()->getPrices()->sum()]))->sum()
            );

            $toCalculate->add($productLineItem);
        }



    }

    private function getPromotions(Context $context, string $productId): EntityCollection
    {

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('productId', $productId));
        $criteria->addFilter(new EqualsFilter('isActive', true));

        return $this->promotionRepository->search($criteria, $context)->getEntities();
    }

    private function addPromotionToLineItem(LineItem $lineItem, EntityCollection $promotionCollection)
    {
        foreach ($promotionCollection as $promotion) {
            $label = $promotion->name;

            $discount = new LineItem(
                $promotion->getId() . '-discount',
                self::DISCOUNT_TYPE,
                $promotion->getId()
            );

            $discount->setRemovable(true)
                ->setStackable(true)
                ->setLabel($label)
                ->setCover($lineItem->getCover())
                ->setQuantity($lineItem->getQuantity())
                ->setGood(false);

            $lineItem->addChild($discount);
        }
    }

    private function addDiscount(LineItemCollection $productLineItems, LineItem $lineItem, EntityCollection $promotions, SalesChannelContext $context)
    {
        foreach ($promotions as $promotion) {
            $lineItemChild = $lineItem->getChildren()->get($promotion->getId() . '-discount');
            $priceDefinition = new PercentagePriceDefinition(
                $promotion->getDiscountRate() * -1,
                new LineItemRule(LineItemRule::OPERATOR_EQ, $productLineItems->getKeys())
            );
            $lineItemChild->setPriceDefinition($priceDefinition)->setGood(false);
        }
    }

//    private function calculatorProduct(LineItemCollection $productLineItems, SalesChannelContext $context)
//    {
//        foreach ($productLineItems as $productLineItem) {
//            $priceDefinition = $productLineItem->getPriceDefinition();
//
//            if ($priceDefinition === null || !$priceDefinition instanceof QuantityPriceDefinition) {
//                throw new \RuntimeException(sprintf('Product "%s" has invalid price definition', $productLineItem->getLabel()));
//            }
//
//            $productLineItem->setPrice(
//                $this->quantityPriceCalculator->calculate($priceDefinition, $context)
//            );
//        }
//    }

    private function calculatorDiscount(LineItem $productLineItem, EntityCollection $promotions, SalesChannelContext $context)
    {
        foreach ($promotions as $promotion) {

            $lineItemChild = $productLineItem->getChildren()->get($promotion->getId() . '-discount');
            $priceDefinition = $lineItemChild->getPriceDefinition();

            $price = $this->percentagePriceCalculator->calculate(
                $priceDefinition->getPercentage(),
                new PriceCollection([$productLineItem->getPrice()]),
                $context
            );


            $lineItemChild->setPrice($price);
        }

    }


}
