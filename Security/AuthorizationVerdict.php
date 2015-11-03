<?php

namespace Visca\Bundle\CoreBundle\Security;

/**
 * Class Authorization.
 */
class AuthorizationVerdict
{
    /**
     * Tell whether is authorized or not.
     *
     * @var bool
     */
    private $authorized;

    /**
     * The reason why it would not be authorized.
     *
     * @var string
     */
    private $reason;

    /**
     * Unique code for the verdict.
     *
     * @var int
     */
    private $code;

    /**
     * @param bool   $authorized Is authorized or not
     * @param int    $code       Unique code for the verdict
     * @param string $reason     The reason why
     */
    public function __construct($authorized, $code = 0, $reason = '')
    {
        $this->authorized = $authorized;
        $this->code = $code;
        $this->reason = $reason;
    }

    /**
     * @return bool
     */
    public function isAuthorized()
    {
        return $this->authorized;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }
}
