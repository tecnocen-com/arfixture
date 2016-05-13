<?php

namespace tecnocen\arfixture\loggers;

use Yii;
use yii\helpers\Console;

/**
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 */
class ConsoleLogger extends \yii\base\Component implements LoggerInterface
{
    /**
     * @var boolean if the logger must show error messages only.
     * @see [[getSilent()]]
     * @see [[setSilent()]]
     */
    private $silent = false;

    /**
     * @var string character to call attention on a failed test.
     */
    public $exclamation;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->exclamation === null) {
            $this->exclamation =  Console::ansiFormat('!', [
                Console::FG_GREEN,
                Console::BG_RED
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function setSilent($silent)
    {
        $this->silent = (bool)$silent;
    }

    /**
     * @inheritdoc
     */
    public function getSilent()
    {
        return $silent;
    }

    /**
     * @inheritdoc
     */
    public function notify($message, $args = [])
    {
        if (!$this->silent) {
            Console::output(Yii::t(
                'yii',
                $message,
                $args
            ));
        }
    }

    /**
     * @inheritdoc
     */
    public function error($message, $args = [])
    {
        Console::error(Yii::t(
            'yii',
            $message,
            $args
        ) . $this->exclamation);
    }

    /**
     * @inheritdoc
     */
    public function formatClass($class)
    {
        return Console::ansiFormat($class, [Console::FG_CYAN]);
    }

    /**
     * @inheritdoc
     */
    public function formatAlias($alias)
    {
        return Console::ansiFormat($alias, [Console::FG_BLUE]);
    }

    /**
     * @inheritdoc
     */
    public function formatAttribute($attribute)
    {
        return Console::ansiFormat($attribute, [Console::FG_YELLOW]);
    }

    /**
     * @inheritdoc
     */
    public function formatMessage($message)
    {
        return Console::ansiFormat($message, [Console::FG_PURPLE]);
    }

    /**
     * @inheritdoc
     */
    public function startFixture($args = [])
    {
        $this->notify(self::MESSAGE_START_FIXTURE, $args);
    }

    /**
     * @inheritdoc
     */
    public function savedRecord($args = [])
    {
        $this->notify(self::MESSAGE_SAVED_RECORD, $args);
    }

    /**
     * @inheritdoc
     */
    public function checkValidation($args = [])
    {
        $this->notify(self::MESSAGE_CHECK_VALIDATION, $args);
    }

    /**
     * @inheritdoc
     */
    public function validationCorrect($args = [])
    {
        $this->notify(self::MESSAGE_VALIDATION_CORRECT, $args);
    }

    /**
     * @inheritdoc
     */
    public function finishFixture($args = [])
    {
        $this->notify(self::MESSAGE_FINISH_FIXTURE, $args);
    }

    /**
     * @inheritdoc
     */
    public function saveException($args = [])
    {
        $this->error(self::ERROR_SAVE_EXCEPTION, $args);
    }

    /**
     * @inheritdoc
     */
    public function validationErrorNotFound($args = [])
    {
        $this->error(self::ERROR_VALIDATION_ERROR_NOT_FOUND, $args);
    }

    /**
     * @inheritdoc
     */
    public function validationMessageNotFound($args = [])
    {
        $this->error(self::ERROR_VALIDATION_MESSAGE_NOT_FOUND, $args);
    }

    /**
     * @inheritdoc
     */
    public function validationUnexpected($args = [])
    {
        $this->error(self::ERROR_VALIDATION_UNEXCPECTED, $args);
    }
}
