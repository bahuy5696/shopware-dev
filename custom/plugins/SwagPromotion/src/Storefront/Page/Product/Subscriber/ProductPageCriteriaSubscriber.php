<?php


namespace SwagPromotion\Storefront\Page\Product\Subscriber;


use Shopware\Storefront\Page\Product\ProductPageCriteriaEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductPageCriteriaSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            ProductPageCriteriaEvent::class => 'onProductCriteriaLoaded',
        ];
    }

    public function onProductCriteriaLoaded(ProductPageCriteriaEvent $event){
        $event->getCriteria()->addAssociation('promotions');
    }
}
