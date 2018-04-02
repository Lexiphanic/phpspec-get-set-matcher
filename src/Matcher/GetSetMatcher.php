<?php

namespace Lexiphanic\PhpSpecGetSetMatcher\Matcher;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\Matcher;

class GetSetMatcher implements Matcher
{
    /**
     * Checks if matcher supports provided subject and matcher name.
     *
     * @param string  $name
     * @param mixed   $subject
     * @param mixed[] $arguments
     *
     * @return bool
     */
    public function supports(string $name, $subject, array $arguments) : bool
    {
        return in_array($name, ['getSet', 'fluentlyGetSet'], true)
            && in_array(count($arguments), [1, 2], true);
    }

    /**
     * Evaluates positive match.
     *
     * @param string  $name
     * @param mixed   $subject
     * @param mixed[] $arguments
     *
     * @throws FailureException
     */
    public function positiveMatch(string $name, $subject, array $arguments)
    {
        $getter = 'get'.ucfirst($arguments[0]);
        $setter = 'set'.ucfirst($arguments[0]);
        if (array_key_exists(2, $arguments)) {
            $result = $this->getter();
            if ($result !== $arguments[2]) {
                throw new FailureException(
                    sprintf(
                        'the default value "%s" should be "%s" but got "%s"',
                        $arguments[0],
                        $arguments[2],
                        $result
                    )
                );
            }
        }

        $result = $this->$setter($value);
        if ($name === 'fluentlyGetSet' && $result !== $subject) {
            throw new FailureException(
                sprintf(
                    'the setter for "%s" is not fluent',
                    $arguments[0]
                )
            );
        }

        $result = $this->$getter();
        if ($result !== $value) {
            throw new FailureException(
                sprintf(
                    'the getter for "%s" should return "%s" but got "%s"',
                    $arguments[0],
                    $arguments[1],
                    $result
                )
            );
        }
    }

    /**
     * Just inverts the positive matcher.
     *
     * @param string  $name
     * @param mixed   $subject
     * @param mixed[] $arguments
     *
     * @throws FailureException
     */
    public function negativeMatch(string $name, $subject, array $arguments)
    {
        try {
            $this->positiveMatch($name, $subject, $arguments);
        } catch (\FailureException $e) {
            return;
        }

        throw new FailureException(
            sprintf(
                'the getter and setter for "%s" passed but this was unexpected"',
                $arguments[0],
            )
        );
    }

    /**
     * Returns matcher priority.
     *
     * @return int
     */
    public function getPriority(): int
    {
        return 0;
    }
}
