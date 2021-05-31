<?php
namespace SwagPromotion\Core\Content\Promotion;

use Shopware\Core\Content\Product\ProductDefinition;
use \Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use SwagPromotion\Core\Content\Promotion\Aggregate\PromotionTranslation\SwagPromotionTranslationDefinition;

class PromotionDefinition extends EntityDefinition
{

    public function getEntityName(): string
    {
        return 'swag_promotion';
    }

    public function getEntityClass(): string
    {
        return PromotionEntity::class;
    }

    public function getCollectionClass(): string
    {
        return PromotionCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new IntField('discount_rate', 'discountRate'))->addFlags(new Required()),
            (new DateField('start_date', 'startDate'))->addFlags(new Required()),
            (new DateField('expired_date', 'expiredDate'))->addFlags(new Required()),
            (new BoolField('is_active', 'isActive'))->addFlags(new Required()),

            //translate field table language of shopware
            new TranslatedField('name'),
            new TranslationsAssociationField(SwagPromotionTranslationDefinition::class, 'swag_promotion_id'),

            //foreign field table product of shopware
            (new ReferenceVersionField(ProductDefinition::class)),
            new FkField('product_id',  'productId', ProductDefinition::class),
            new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id', 'true'),
        ]);
    }
}
