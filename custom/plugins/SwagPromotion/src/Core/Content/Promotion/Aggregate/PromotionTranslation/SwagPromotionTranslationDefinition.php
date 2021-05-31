<?php


namespace SwagPromotion\Core\Content\Promotion\Aggregate\PromotionTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use SwagPromotion\Core\Content\Promotion\PromotionDefinition;

class SwagPromotionTranslationDefinition extends EntityTranslationDefinition
{

    public function getEntityName(): string
    {
       return 'swag_promotion_translation';
    }
    public function getEntityClass(): string
    {
        return SwagPromotionTranslationEntity::class;
    }

    public function getCollectionClass(): string
    {
        return SwagPromotionTranslationCollection::class;
    }

    public function getParentDefinitionClass(): string
    {
        return PromotionDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
        ]);
    }
}
