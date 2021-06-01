<?php


namespace SwagPromotion\Core\Api;


use Faker\Factory;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"api"})
 */
class PromotionController extends AbstractController
{
    /**
     * @var EntityRepositoryInterface
     */
    private $productRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $promotionRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $promotionTranslationRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $languageRepository;

    public function __construct(EntityRepositoryInterface $productRepository,
                                EntityRepositoryInterface $promotionRepository,
                                EntityRepositoryInterface $promotionTranslationRepository,
                                EntityRepositoryInterface $languageRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->promotionRepository = $promotionRepository;
        $this->promotionTranslationRepository = $promotionTranslationRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @Route("/api/promotion/generate", name="api.custom.swag_promotion.generate", methods={"GET"})
     * @param Context $context
     */
    public function generateData(Context $context)
    {
        return $this->json($this->createRandomPromotion($context));
    }

    private function getProducts(Context $context): EntityCollection
    {
        $criteria = new Criteria();
        $criteria->setLimit(5);
        $criteria->addFilter(new EqualsFilter('active', 1));
        $products = $this->productRepository->search($criteria, $context);
        return $products->getEntities();
    }

    private function getCurrentLanguage(Context $context){
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', 'English'));

        $language = $this->languageRepository->search($criteria, $context);
        return $language->getEntities()->first();

    }

    /**
     * @param Context $context
     * @return EntityCollection
     */
    private function createRandomPromotion(Context $context): EntityCollection
    {
        $product = $this->getProducts($context)->getKeys();
        $faker = Factory::create();
        $promotionData = [];
        $translationData = [];
        $n = 0;

        while ($n < 5){
            $date = date('Y-m-d');
            $discount = $faker->numberBetween(20,45) . '%';
            $id = Uuid::randomHex();
            $promotionData[] = [
                'id' => $id,
                'discountRate' => $faker->numberBetween(20,45),
                'startDate' => date('Y-m-d', strtotime($date.'-5 days')),
                'expiredDate' => date('Y-m-d', strtotime($date.'+5 days')),
                'isActive' => $faker->boolean(),
                'productId' => $product[$n]
            ];
            $translationData[] = [
                'swagPromotionId' => $id,
                'name' => $faker->streetName . ' ' . $discount,
                'languageId' => $this->getCurrentLanguage($context)->getId()
            ];
            $n++;
        }

        $this->promotionRepository->create($promotionData, $context);
        $this->promotionTranslationRepository->create($translationData, $context);

        $criteria = new Criteria();
        $criteria->addSorting(new FieldSorting('createdAt', FieldSorting::DESCENDING));
        $promotions = $this->promotionRepository->search($criteria,$context);

        return $promotions->getEntities();

    }


}
