<?php
/**
 * ctaform plugin for Craft CMS 3.x
 *
 * Plugin for CTA Form
 *
 * @link      https://marcozellweger.ch
 * @copyright Copyright (c) 2021 Marco Zellweger
 */

namespace mz\ctaform\models;

use mz\ctaform\Ctaform;

use Craft;
use craft\base\Model;

/**
 * Ctaform Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Marco Zellweger
 * @package   Ctaform
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * The emailaddress to where contactform submissions should be send
     *
     * @var string
     */
    public $ctaEmail;

    /**
     * The success message
     * 
     * @var string
     */
    public $ctaSuccessMessage;

    /**
     * The error message
     * 
     * @var string
     */
    public $ctaErrorMessage;

    /**
     * The message for for Screenreaders while the form is sending 
     * 
     * @var string
     */
    public $a11ySendingAnnouncement;


    /**
     * @var bool
     */
    public $ctaDevMode;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['ctaEmail', 'email', 'message' => 'Keine gültige Emailadresse eingegeben'],
            ['ctaSuccessMessage', 'default', 'value' => Craft::t('ctaform', 'Das Formular wurde erfolgreich versendet.')],
            ['ctaSuccessMessage', 'string'],
            ['ctaErrorMessage', 'default', 'value' => Craft::t('ctaform', 'Ein Fehler ist aufgetreten.')],
            ['ctaErrorMessage', 'string'],
            ['ctaDevMode', 'boolean'],
            ['a11ySendingAnnouncement', 'default', 'value' => Craft::t('ctaform', 'Das Formular wird versendet.')]
        ];
    }
}
