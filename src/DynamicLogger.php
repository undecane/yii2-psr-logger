<?php

namespace Zing\YiiPsrLogger;

use Psr\Log\AbstractLogger;
use yii\log\Logger as YiiLogger;

/**
 * Implements a PSR logger that routes messages to the current Yii Logger.
 */
class DynamicLogger extends AbstractLogger
{
    /**
     * @var string
     */
    private $category;
    /**
     * @var \Zing\YiiPsrLogger\Logger|null
     */
    private $logger;

    /**
     * @var YiiLogger|null
     */
    private $yiiLogger;

    public function __construct($category = 'application')
    {
        $this->category = $category;
    }

    /**
     * Get current Yii Logger.
     */
    private function getLogger()
    {
        if (! ($this->logger instanceof Logger && $this->yiiLogger instanceof YiiLogger) || \Yii::getLogger() !== $this->yiiLogger) {
            $this->yiiLogger = \Yii::getLogger();
            $this->logger = new Logger(\Yii::getLogger(), $this->category);
        }

        return $this->logger;
    }

    /**
     * @param string $level
     * @param \Stringable|string $message
     * @param array<mixed> $context
     */
    public function log($level, $message, array $context = [])
    {
        $this->getLogger()
            ->log($level, $message, $context);
    }
}
