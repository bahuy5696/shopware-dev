<?php

namespace SwagPromotion\Core\Content\Promotion\Aggregate\PromotionTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use SwagPromotion\Core\Content\Promotion\PromotionEntity;

class SwagPromotionTranslationEntity extends Entity
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string
     */
    protected $promotionId;

    /**
     * @var PromotionEntity
     */
    protected $promotion;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPromotionId(): string
    {
        return $this->promotionId;
    }

    /**
     * @param string $promotionId
     */
    public function setPromotionId(string $promotionId): void
    {
        $this->promotionId = $promotionId;
    }

    /**
     * @return PromotionEntity
     */
    public function getPromotion(): PromotionEntity
    {
        return $this->promotion;
    }

    /**
     * @param PromotionEntity $promotion
     */
    public function setPromotion(PromotionEntity $promotion): void
    {
        $this->promotion = $promotion;
    }

}
