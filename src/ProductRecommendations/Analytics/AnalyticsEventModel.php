<?php

namespace Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\Analytics;

use Sparkfbt\SparkPlugins\SparkWoo\Common\Models\AbstractModel;
use Sparkfbt\SparkPlugins\SparkWoo\Common\Models\ModelInterface;
use Sparkfbt\SparkPlugins\SparkWoo\Common\Plugins\GlobalVariables;
class AnalyticsEventModel extends AbstractModel implements ModelInterface
{
    protected static string $tablePrefix = GlobalVariables::SPARKWOO_PREFIX;
    protected static string $tableName = 'pr_event';
    const RENDER = 'render';
    const CLICK = 'click';
    const CONVERSION = 'conversion';
    public int $id;
    public string $timestamp;
    public string $event;
    public string $sessionId;
    public int $productId;
    public string $plugin;
    public string $placementHook;
    public string $productsManager;
    public int $recommendationId;
    public int $userId;
    public int $orderId;
    public int $orderItemId;
}
