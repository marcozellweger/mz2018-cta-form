<?php
/**
 * mzSchemaData module for Craft CMS 3.x
 *
 * Module for creating schema.org data
 *
 * @link      https://marcozellweger.ch
 * @copyright Copyright (c) 2018 Marco Zellweger
 */

namespace mz\ctaform\variables;

use Craft;

/**
 * mzSchemaData Variable
 *
 * Craft allows modules to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.mzSchemaDataModule }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Marco Zellweger
 * @package   MzSchemaDataModule
 * @since     1.0.0
 */
class ctaVariables
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.mzSchemaDataModule.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.mzSchemaDataModule.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */

    public function sendingAnnouncement() {

        return Ctaform::getInstance()->getSettings()->a11ySendingAnnouncement;
    }
}
