<?php
namespace SwagPromotion\Core\Content\Promotion;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class PromotionCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return PromotionEntity::class;
    }
}
