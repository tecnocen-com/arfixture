<?php

namespace tecnocen\arfixture;

use tecnocen\arfixture\loggers\ConsoleLogger;
use tecnocen\arfixture\loggers\LoggerInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 */
class ARFixture extends \yii\test\ActiveFixture
{
    /**
     * @var string scenario to be used when its not defined on the record.
     */
    public $scenarioDefault = Model::SCENARIO_DEFAULT;

    /**
     * @var Logger used for the `notify()` and `error()`` methods
     */
    public $logger = [];

    /**
     * @var int number of tests passed.
     */
    public $passed = 0;

    /**
     * @var int number of tests failed
     */
    public $failed = 0;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->modelClass)) {
            throw new InvalidConfigException(
                '`$modelClass` can not be blank.'
            );
        }
        parent::init();
        if (is_array($this->logger)) {
            $this->logger = Yii::createObject(ArrayHelper::merge(
                ['class' => ConsoleLogger::className()],
                $this->logger
            ));
        }
        if (!$this->logger instanceof LoggerInterface) {
            throw new InvalidConfigException(
                '`$logger` must impelement the '
                . '`tecnocen\\arfixture\\loggers\\LoggerInterface`.'
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function load()
    {
        $formattedClass = $this->logger->formatClass(static::className());
        $this->logger->startFixture(['class' => $formattedClass]);

        $this->resetTable();
        $this->data = [];
        foreach ($this->getData() as $alias => $record) {
            $this->data[$alias] = $this->evaluateRecord($alias, $record);
        }

        $this->logger->finishFixture([
            'class' => $formattedClass,
            'passed' => $this->passed,
            'failed' => $this->failed,
        ]);
    }

    /**
     * Instantiate a model using [[ActiveRecord::instantiate()]] method, then
     * asigns the scenario defined
     *
     * @param string $alias used as reference for the record.
     * @param array $record data to be evaluated for the record
     * The following options are specially handled:
     *
     * - attributeErrors: array list of expected validation errors in the format
     *   ```php
     *   [
     *       'attribute1', // will check that it contains any error.
     *       'attribute2' => 'Error Message' // This has to be the error found.
     *   ]
     *   ```
     *   > Warning: If this option is defined the record won't be saved even if
     *   > it passes all the validations.
     *
     * - scenario: string to be used as scenario for the model to handle
     *   the methods `Model::load()` and `Model::validate()`, if not defined
     *   the [[$scenarioDefault]] will be used
     *
     * @return ActiveRecord
     */
    protected function evaluateRecord($alias, $record)
    {
        $formattedAlias = $this->logger->formatAlias($alias);

        $attributeErrors = ArrayHelper::remove($record, 'attributeErrors', []);
        $modelClass = $this->modelClass;
        $model = $modelClass::instantiate($record);
        $model->scenario = ArrayHelper::remove(
            $record,
            'scenario',
            $this->scenarioDefault
        );

        $model->load($record, '');

        if ($model->validate() && empty($attributeErrors)) {
            // no error ocurred and no error was expected, proceed to save
            try {
                $model->save(false);
                $this->logger->savedRecord(['alias' => $formattedAlias]);
                $this->passed++;
                return $model;
            } catch (\Exception $e) {
                $this->logger->saveException([
                    'alias' => $formattedAlias,
                    'exception' => $this->logger->formatClass(get_class($e)),
                    'message' => $this->logger->formatMessage($e->getMessage()),
                ]);
                $this->failed++;
                return $model;
            }
        }

        $this->logger->checkValidation(['alias' => $formattedAlias]);
        $this->checkErrors($model->getFirstErrors(), $attributeErrors);
        return $model;
    }

    /**
     * Compares the validation errors from the model with the errors expected
     * for that model defined in `attributeErrors` element on the data.
     *
     * @param array $modelErrors validation errors in the format:
     * ```php
     * [
     *     'attribute1' => 'Error Message',
     *     'attribute2' => 'Error Message'
     * ]
     * ```
     * @param array $attributeErrors expected validation errors in the format:
     * ```php
     * [
     *     'attribute1', // will check that it contains any error.
     *     'attribute2' => 'Error Message' // The first error match the value
     * ]
     * ```
     */
    public function checkErrors($modelErrors, $attributeErrors)
    {
        foreach ($attributeErrors as $key => $value) {
            if (is_int($key)) {
                $attribute = $value;
                $message = null;
            } else {
                $attribute = $key;
                $message = $value;
            }

            $error = ArrayHelper::remove($modelErrors, $attribute);
            if (empty($error)) {
                $this->logger->validationErrorNotFound([
                    'attribute' => $this->logger->formatAttribute($attribute),
                ]);
                $this->failed++;
                continue;
            }
            if (!empty($message) && $error != $message) {
                $this->logger->validationMessageNotFound([
                    'attribute' => $this->logger->formatAttribute($attribute),
                    'message' => $this->logger->formatMessage($message),
                    'error' => $this->logger->formatMessage($error),
                ]);
                $this->failed++;
                continue;
            }

            $this->logger->validationCorrect([
                'attribute' => $this->logger->formatAttribute($attribute),
            ]);
            $this->passed++;
        }

        foreach ($modelErrors as $attribute => $error) {
            $this->logger->validationUnexpected([
                'attribute' => $this->logger->formatAttribute($attribute),
                'error' => $this->logger->formatMessage($error),
            ]);
            $this->failed++;
        }
    }

    /**
     * Gets the data for the fixture if [[$dataFile]] is not defined then it
     * will seek on the `data/` subfolder the file with the same name as this
     * class except for the `Fixture` keyword at the end. Example:
     * `UserFixture` will return the file `data/User.php` or an empty array when
     * the file can not found.
     *
     * You can rewrite this method to directly return the data too.
     *
     * @return array
     * @see evaluateRecord()
     */
    protected function getData()
    {
        if ($this->dataFile === null) {
            $class = new \ReflectionClass($this);
            $classFile = $class->getFileName();
            $dir = dirname($class->getFileName());
            $dataFile = $dir
                . '/data'
                . strtr($classFile, [$dir => '', 'Fixture' => '']);

            return is_file($dataFile) ? require($dataFile) : [];
        }

        return parent::getData();
    }
}
