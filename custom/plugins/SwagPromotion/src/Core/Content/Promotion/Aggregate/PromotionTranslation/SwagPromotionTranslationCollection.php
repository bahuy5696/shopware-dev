<?php

namespace SwagPromotion\Core\Content\Promotion\Aggregate\PromotionTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class SwagPromotionTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return SwagPromotionTranslationEntity::class;
    }
}
