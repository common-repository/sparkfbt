<?php

namespace Sparkfbt\SparkPlugins\SparkWoo\ProductRecommendations\Analytics;

use Sparkfbt\SparkPlugins\SparkWoo\Common\Factories\AbstractFactory;
use Sparkfbt\SparkPlugins\SparkWoo\Common\Factories\FactoryInterface;
class AnalyticsEventFactory extends AbstractFactory implements FactoryInterface
{
    protected static string $model = AnalyticsEventModel::class;
    public function default() : array
    {
        $userId = get_current_user_id();
        return array('sessionId' => AnalyticsModule::getSessionHash(), 'userId' => 0 == $userId ? null : $userId);
    }
}
