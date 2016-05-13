<?php

namespace tecnocen\arfixture\loggers;

/**
 * @author Angel (Faryshta) Guevara <angeldelcaos@gmail.com>
 * @property boolean $silent if this logger must show error messages only.
 */
interface LoggerInterface
{
    /**
     * Message used when the fixture starts.
     * @see startFixture()
     */
    const MESSAGE_START_FIXTURE = '{class} fixture loading';

    /**
     * Message used when a record is saved successfully.
     * @see savedRecord()
     */
    const MESSAGE_SAVED_RECORD = '  {alias} saved correctly';

    /**
     * Message used when a record will compare expected validation.
     * @see checkValidation()
     */
    const MESSAGE_CHECK_VALIDATION = '  {alias} Checking validation';

    /**
     * Message used when an attribute is validated correctly.
     * @see validationCorrect()
     */
    const MESSAGE_VALIDATION_CORRECT = '    {attribute} validated correctly';

    /**
     * Message used when the fixture finished.
     * @see finishFixture()
     */
    const MESSAGE_FINISH_FIXTURE =
        '{class} finished with {passed} passed / {failed} failed';

    /**
     * Error message used when saving a record throws an exception.
     * @see saveException()
     */
    const ERROR_SAVE_EXCEPTION =
        '  {alias} throwed {exception} with message {message}';

    /**
     * Error message used when an expected validation error is not found.
     * @see validationErrorNotFound()
     */
    const ERROR_VALIDATION_ERROR_NOT_FOUND =
        '    {attribute} expected error not found';

    /**
     * Error message used when an expected validation message is not found.
     * @see validationMessageNotFound()
     */
    const ERROR_VALIDATION_MESSAGE_NOT_FOUND =
        '    {attribute} error message "{message}" doesn\'t match "{error}"';

    /**
     * Error message used when validation find an unexpected error.
     * @see validationUnexpected()
     */
    const ERROR_VALIDATION_UNEXCPECTED =
        '    {attribute} unexpected validation error "{error}"';

    /**
     * @param boolean $silent if this logger must show error messages only.
     */
    public function setSilent($silent);

    /**
    * @return boolean $silent if this logger must show error messages only.
     */
    public function getSilent();

    /**
     * If [[getSilent()]] returns 'false' notify a message.
     *
     * @param string $message
     * @param string $args argument to render the message
     * @see setSilent()
     * @see getSilent()
     */
    public function notify($message, $args = []);

    /**
     * Show an error message.
     *
     * @param string $message
     * @param string $args argument to render the message
     */
    public function error($message, $args = []);

    /**
     * Applies format to distinguish class names.
     *
     * @param string $class
     * @return string
     */
    public function formatClass($class);

    /**
     * Applies format to distinguish record alias.
     *
     * @param string $alias
     * @return string
     */
    public function formatAlias($alias);

    /**
     * Applies format to distinguish attribute names.
     *
     * @param string $attribute
     * @return string
     */
    public function formatAttribute($attribute);

    /**
     * Applies format to distinguish messages.
     *
     * @param string $message
     * @return string
     */
    public function formatMessage($message);

    /**
     * Notification that the fixture started
     * @param array $args arguments to render the message. Admits options:
     * - class: string class name of the fixture.
     * @see notify()
     */
    public function startFixture($args = []);

    /**
     * Notification that the record was saved successfully.
     * @param array $args arguments to render the message. Admits options:
     * - alias: string alias name of the record.
     * @see notification()
     */
    public function savedRecord($args = []);

    /**
    * Notification that the record will compare the expected validation
    * @param array $args arguments to render the message. Admits options:
    * - alias: string alias name of the record.
    * @see notify()
    */
    public function checkValidation($args = []);

    /**
     * Notification when an attribute is validated correctly.
     * @param array $args arguments to render the message. Admits options:
     * - attribute: string attribute name.
     * @see notify()
     */
    public function validationCorrect($args = []);

    /**
     * Notification that the fixture finished
     * @param array $args arguments to render the message. Admits options:
     * - class: string class name of the fixture.
     * - passed: integer tests passed.
     * - failed: integer tests failed
     * @see notify()
     */
    public function finishFixture($args = []);

    /**
     * Error notification that saving a record throws an exception
     * @param array $args arguments to render the message. Admits options:
     * - alias: string alias name of the record.
     * - exception: string class name of the exception.
     * - message: string message found on the exception.
     * @see error()
     */
    public function saveException($args = []);

    /**
     * Error notfication that an expected validation error was not found.
     * @param array $args arguments to render the message. Admits options:
     * - attribute: string attribute name.
     * @see error()
     */
    public function validationErrorNotFound($args = []);

    /**
     * Error notfication when an expected validation message was not found.
     * @param array $args arguments to render the message. Admits options:
     * - attribute: string attribute name.
     * - message: string expected error message.
     * - error: string received error message.
     * @see error()
     */
    public function validationMessageNotFound($args = []);

    /**
     * Notification that the validation found an unexpected error.
     * @param array $args arguments to render the message. Admits options:
     * - attribute: string attribute name.
     * - error: string received error message.
     * @see error()
     */
    public function validationUnexpected($args = []);
}
