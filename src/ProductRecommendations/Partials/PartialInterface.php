<?php

namespace Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\Partials;

use Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\Models\ProductRecommendationPostModel;
use Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\ProductPlacementHooks\ProductPlacementHookInterface;
interface PartialInterface
{
    /**
     * @var ProductRecommendationPostModel $productRecommendationPostModel
     * @var \WC_Product[] $products
     */
    public function render(ProductRecommendationPostModel $productRecommendationPostModel, array $products, ProductPlacementHookInterface $placementHook = null);
}
