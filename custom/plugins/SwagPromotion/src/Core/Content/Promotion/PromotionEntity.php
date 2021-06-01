<?php

namespace SwagPromotion\Core\Content\Promotion;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class PromotionEntity extends Entity
{
    use EntityIdTrait;
    /**
     * @var string
     */
    protected $productId;

    /**
     * @var int
     */
    protected $discountRate;
    /**
     * @var string
     */
    protected $startDate;
    /**
     * @var string
     */
    protected $expiredDate;
    /**
     * @var bool
     */
    protected $isActive;

    /**
     * @return int
     */
    public function getDiscountRate(): int
    {
        return $this->discountRate;
    }

    /**
     * @param int $discountRate
     */
    public function setDiscountRate(int $discountRate): void
    {
        $this->discountRate = $discountRate;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->startDate;
    }

    /**
     * @param string $startDate
     */
    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return string
     */
    public function getExpiredDate(): string
    {
        return $this->expiredDate;
    }

    /**
     * @param string $expiredDate
     */
    public function setExpiredDate(string $expiredDate): void
    {
        $this->expiredDate = $expiredDate;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @param string $productId
     */
    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }



}
