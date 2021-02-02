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
 * CtaformModel Model
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
class FormData extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $fromName;

    /**
     * @var string
     */
    public $fromGivenName;

    /**
     * @var string
     */
    public $fromEmail;

    /**
     * @var string
     */
    public $fromPhone;

    /**
     * @var string
     */
    public $fromStreet;

    /**
     * @var string
     */
    public $fromZip;

    /**
     * @var string
     */
    public $fromCity;

    /**
     * @var boolean
     */
    public $test;

    /**
     * @var string
     */
    public $contact;

    /**
     * @var array
     */
    public $service;


    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [
            'fromName'      => \Craft::t('ctaform', 'Name'),
            'fromGivenName' => \Craft::t('ctaform', 'Given Name'),
            'fromEmail'     => \Craft::t('ctaform', 'Email'),
            'fromPhone'      => \Craft::t('ctaform', 'Phone'),
        ];
    }

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
            [['fromName', 'fromGivenName', 'fromEmail', 'fromPhone', 'fromStreet', 'fromZip', 'fromCity', 'contact'], 'string'],
            [['fromName', 'fromGivenName', 'fromEmail'], 'required'],
            [['fromEmail'], 'email'],
            [['fromStreet', 'fromZip', 'fromCity'], 'required', 'when' => function() {
                return $this->contact == 'postcard';
            }],
            ['fromPhone', 'required', 'when' => function() {
                return $this->contact == 'phone';
            }],
        ];
    }
}
