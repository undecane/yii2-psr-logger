<?php

namespace Zing\YiiPsrLogger\Tests;

use yii\log\Logger as YiiLogger;
use Zing\YiiPsrLogger\DynamicLogger;

/**
 * @internal
 */
final class DynamicLoggerTest extends TestCase
{
    public function testLoggerUsesCurrent()
    {
        $mockObject = $this->getMockBuilder(YiiLogger::className())->getMock();
        $mockObject->expects($this->once())
            ->method('log')
            ->with('test1 []', YiiLogger::LEVEL_INFO);

        $yiiLogger2 = $this->getMockBuilder(YiiLogger::className())->getMock();
        $yiiLogger2->expects($this->once())
            ->method('log')
            ->with('test2 []', YiiLogger::LEVEL_INFO);

        $dynamicLogger = new DynamicLogger();
        \Yii::setLogger($mockObject);

        $dynamicLogger->info('test1');

        \Yii::setLogger($yiiLogger2);
        $dynamicLogger->info('test2');
    }
}
