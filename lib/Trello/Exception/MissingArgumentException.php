<?php

namespace Trello\Exception;

/**
 * MissingArgumentException
 *
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
class MissingArgumentException extends ErrorException
{
    /**
     * @param string|array $required
     */
    public function __construct(string|array $required, int $code = 0, $previous = null)
    {
        if (is_string($required)) {
            $required = [$required];
        }

        parent::__construct(sprintf('One or more of required ("%s") parameters are missing!', implode('", "', $required)), $code, $previous);
    }
}
