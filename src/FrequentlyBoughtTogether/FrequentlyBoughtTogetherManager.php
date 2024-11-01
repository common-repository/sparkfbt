<?php

namespace Sparkfbt\SparkPlugins\SparkWoo\FrequentlyBoughtTogether;

use Sparkfbt\SparkPlugins\SparkWoo\Common\Cache\CacheManager;
use Sparkfbt\SparkPlugins\SparkWoo\Common\Loader;
use Sparkfbt\SparkPlugins\SparkWoo\Common\Modules\ModuleInterface;
use Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\Models\ProductRecommendationPostModel;
use Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\ProductsManager\AbstractProductsManager;
use Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\ProductsManager\ProductsManagerInterface;
use Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\ProductsManager\RecommendedProductIdsCollection;
class FrequentlyBoughtTogetherManager extends AbstractProductsManager implements ProductsManagerInterface
{
    private const CACHE_KEY = 'fbt-table';
    protected CacheManager $cacheManager;
    public function __construct($slug, $title, $description, $shortcode, CacheManager $cacheManager)
    {
        parent::__construct($slug, $title, $description, $shortcode);
        $this->cacheManager = $cacheManager;
    }
    public function getRecommendedProductIdsCollection(ProductRecommendationPostModel $postModel) : RecommendedProductIdsCollection
    {
        global $wpdb;
        global $product;
        $currentProductIds = array();
        $singleProductId = \false;
        if (\function_exists('is_checkout') && \is_checkout() || \function_exists('is_cart') && \is_cart()) {
            $currentProductIds = \array_column(WC()->cart->get_cart(), 'product_id');
        } else {
            if (is_single() && $product) {
                $singleProductId = $product->get_id();
                $currentProductIds = array($singleProductId);
            } else {
                return new RecommendedProductIdsCollection(array());
            }
        }
        $results = array();
        foreach ($currentProductIds as $pid) {
            $cacheKey = self::CACHE_KEY . '-' . $pid;
            $pidResults = $this->cacheManager->get($cacheKey);
            if (!$pidResults) {
                $pidResults = $wpdb->get_results($wpdb->prepare("SELECT a.product_id AS productId, b.product_id AS boughtWithProductId, count(*) as amount\n            FROM {$wpdb->prefix}wc_order_product_lookup AS a\n            INNER JOIN {$wpdb->prefix}wc_order_product_lookup AS b ON a.order_id = b.order_id\n            AND a.product_id != b.product_id\n            WHERE a.product_id = %d\n            GROUP BY a.product_id, b.product_id\n            ORDER BY amount DESC\n            LIMIT 20\n            ;", $pid));
                \usort($pidResults, function ($a, $b) {
                    $aa = $a->amount;
                    $ba = $b->amount;
                    if ($aa === $ba) {
                        return \rand(-1, 1);
                    }
                    return $b->amount <=> $a->amount;
                });
                $this->cacheManager->set($cacheKey, $pidResults);
            }
            $results = $pidResults;
        }
        $ids = \array_unique(\array_filter(\array_map(function ($result) {
            return $result->boughtWithProductId;
        }, $results), function ($id) use($currentProductIds) {
            return !\in_array($id, $currentProductIds);
        }));
        $designSettings = $postModel->get('designSettings');
        $showShopTheCombination = \array_key_exists('showAddAllToCart', $designSettings) ? $designSettings['showAddAllToCart'] : \false;
        if (\false !== $singleProductId && $showShopTheCombination && \count($ids) > 0) {
            \array_unshift($ids, $singleProductId);
        }
        return (new RecommendedProductIdsCollection($ids))->filterCurrentlyInCart();
    }
}
