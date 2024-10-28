<?php

namespace Zing\YiiPsrLogger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use yii\helpers\Json;
use yii\log\Logger as YiiLogger;

/**
 * Implements a PSR logger that routes messages to the Yii Logger.
 */
class Logger extends AbstractLogger
{
   /**
     * @var string
     */
    private $category;
    /**
     * @var YiiLogger
     */
    private $yiiLogger;

    /**
     * @var array<string, int>
     */
    private $formatLevelMap = [
        LogLevel::EMERGENCY => YiiLogger::LEVEL_ERROR,
        LogLevel::ALERT => YiiLogger::LEVEL_ERROR,
        LogLevel::CRITICAL => YiiLogger::LEVEL_ERROR,
        LogLevel::ERROR => YiiLogger::LEVEL_ERROR,
        LogLevel::WARNING => YiiLogger::LEVEL_WARNING,
        LogLevel::NOTICE => YiiLogger::LEVEL_WARNING,
        LogLevel::INFO => YiiLogger::LEVEL_INFO,
        LogLevel::DEBUG => YiiLogger::LEVEL_INFO,
    ];

    public function __construct(YiiLogger $yiiLogger = null, $category = 'application')
    {
        $this->category = $category;
        $this->yiiLogger = isset($yiiLogger) ? $yiiLogger : \Yii::getLogger();
    }

    /**
     * @param mixed $level
     * @param mixed $message
     * @param array<mixed> $context
     */
    public function log($level, $message, array $context = [])
    {
        if (! \is_string($level)) {
            throw new \InvalidArgumentException('This logger only supports string levels');
        }

        if (! isset($this->formatLevelMap[$level])) {
            throw new \InvalidArgumentException(\sprintf('Unknown logging level %s', $level));
        }

        $this->yiiLogger->log(
            $message . ' ' . Json::encode($context),
            $this->formatLevelMap[$level],
            $this->category
        );
    }
}
