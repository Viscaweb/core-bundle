<?php

namespace Visca\Bundle\CoreBundle\Entity\Traits;

/**
 * Trait adding enabled/disabled fields and methods.
 */
trait EnabledTrait
{
    /**
     * @var bool
     *
     * Enabled
     */
    protected $enabled;

    /**
     * Set if is enabled.
     *
     * @param bool $enabled enabled value
     *
     * @return $this self Object
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get is enabled.
     *
     * @return bool is enabled
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Enable.
     *
     * @return $this self Object
     */
    public function enable()
    {
        return $this->setEnabled(true);
    }

    /**
     * Disable.
     *
     * @return $this self Object
     */
    public function disable()
    {
        return $this->setEnabled(false);
    }
}
