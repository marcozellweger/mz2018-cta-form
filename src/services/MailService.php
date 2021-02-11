<?php
/**
 * ctaform plugin for Craft CMS 3.x
 *
 * Plugin for CTA Form
 *
 * @link      https://marcozellweger.ch
 * @copyright Copyright (c) 2021 Marco Zellweger
 */

namespace mz\ctaform\services;

use mz\ctaform\Ctaform;
use mz\ctaform\models\FormData;
use Craft;
use craft\mail\Mailer;
use craft\mail\Message;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Markdown;

/**
 * CtaformService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Marco Zellweger
 * @package   Ctaform
 * @since     1.0.0
 */
class MailService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Ctaform::$plugin->ctaformService->exampleService()
     *
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (Ctaform::$plugin->getSettings()->ctaEmail) {
        }

        return $result;
    }

    /**
     * Sends an email submitted through a contact form
     * 
     * @param FormData $formdata
     * @return bool
     */

    public function test() {
        return 'Servicetest was successful';
    }

    /**
     * Compiles the real email textual body from the submitted message.
     *
     * @param FormData $formdata
     * @return string
     */
    public function compileTextBody(FormData $formdata): string
    {
        $fields = [];

        $fields[Craft::t('ctaform', 'Name')] = $formdata->fromName;
        $fields[Craft::t('ctaform', 'Given Name')] = $formdata->fromGivenName;
        $fields[Craft::t('ctaform', 'Email')] = $formdata->fromEmail;
        

        if ($formdata->fromPhone) {
            $fields[Craft::t('ctaform', 'Phone')] = $formdata->fromPhone;
        }

        if ($formdata->fromStreet) {
            $fields[Craft::t('ctaform', 'Street')] = $formdata->fromStreet;
        }

        if ($formdata->fromZip) {
            $fields[Craft::t('ctaform', 'ZIP')] = $formdata->fromZip;
        }

        if ($formdata->fromCity) {
            $fields[Craft::t('ctaform', 'City')] = $formdata->fromCity;
        }

        if ($formdata->contact) {
            $fields[Craft::t('ctaform', 'Contact by')] = $formdata->contact;
        }
        
        if ($formdata->service) {
            $fields[Craft::t('ctaform', 'Services')] = $formdata->service;
        }

        if ($formdata->fromMessage) {
            $fields[Craft::t('ctaform', 'Nachricht')] = $formdata->fromMessage;
        }

        $text = '';

        foreach ($fields as $key => $value) {
            $text .= ($text ? "\n" : '')."- **{$key}:** ";
            if (is_array($value)) {
                $text .= implode(', ', $value);
            } else {
                $text .= $value;
            }
        }

        return $text;
    }

    /**
     * Compiles the real email HTML body from the compiled textual body.
     *
     * @param string $textBody
     * @return string
     */
    public function compileHtmlBody(string $textBody): string
    {
        $html = Html::encode($textBody);
        $html = Markdown::process($html);

        return $html;
    }
}
