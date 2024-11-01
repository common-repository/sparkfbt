<?php

namespace Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\ProductPlacementHooks;

use Sparkfbt\SparkPlugins\SparkWoo\Common\Modules\ModuleInterface;
use Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\Models\ProductRecommendationPostModel;
use Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\ProductsManager\ProductsManagerInterface;
interface ProductPlacementHookInterface extends \JsonSerializable, ModuleInterface
{
    public function getKey();
    public function render();
    public function renderSingle(ProductRecommendationPostModel $productRecommendationPostModel);
    public function getProductsManager() : ProductsManagerInterface;
    public function getAnalyticsEventKey() : string;
    public function getAnalyticsEventEncodedValue(ProductRecommendationPostModel $productRecommendationPostModel) : string;
}
