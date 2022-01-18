<?php
/**
 * ctaform plugin for Craft CMS 3.x
 *
 * Plugin for CTA Form
 *
 * @link      https://marcozellweger.ch
 * @copyright Copyright (c) 2021 Marco Zellweger
 */

namespace mz\ctaform\controllers;

use mz\ctaform\Ctaform;
use mz\ctaform\models\FormData;
use Craft;
use craft\web\Controller;
use craft\mail\Mailer;
use craft\mail\Message;
use yii\web\Response;
use yii\base\InvalidConfigException;
use craft\helpers\StringHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Marco Zellweger
 * @package   Ctaform
 * @since     1.0.0
 */
class SubmitController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'test'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/ctaform/default
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();
        $plugin = Ctaform::getInstance();
        $settings = $plugin->getSettings();

        $formdata = new FormData();
        $formdata->fromName = $request->getBodyParam('lname');
        $formdata->fromGivenName = $request->getBodyParam('fname');
        $formdata->fromEmail = $request->getBodyParam('email');
        $formdata->fromPhone = $request->getBodyParam('phone');
        $formdata->fromStreet = $request->getBodyParam('street');
        $formdata->fromZip = $request->getBodyParam('zip');
        $formdata->fromCity = $request->getBodyParam('city');
        $formdata->contact = $request->getBodyParam('contact');
        $formdata->service = $request->getBodyParam('service');
        $formdata->fromMessage = $request->getBodyParam('message');

        // Validate settings
        if (!$settings->validate()) {
            throw new InvalidConfigException('The contact Form settings do not validate');
        }

        // Validate form data
        if (!$formdata->validate()) {
            if (Craft::$app->request->acceptsJson) {
                return $this->asJson(
                    [
                        'errors' => $formdata->getErrors(),
                        'message' => Craft::t('ctaform', 'form_error'),
                    ]
                );
            }
        }

        $test = $request->getBodyParams();

        $mailer = new Mailer();

        // Grab the to email set in plugin settings
        $toEmail = $settings->ctaEmail;

        // Prepare the message
        $textBody = Ctaform::$plugin->mailservice->compileTextBody($formdata);
        $htmlBody = Ctaform::$plugin->mailservice->compileHtmlBody($textBody);
        
        // Create the message
        $message = (new Message())
            ->setTo($toEmail)
            ->setFrom([$formdata->fromEmail => $formdata->fromName])
            ->setSubject('Anfrage über Kontaktformular')
            ->setTextBody($textBody)
            ->setHTMLBody($htmlBody);

        // if dev mode is on
        if ( $settings->ctaDevMode == '1') {
            // Save email as file for dev purpose
            $mailer->fileTransportPath = '@runtime/mail';
            $mailer->useFileTransport = true;
        }

        if (!$mailer->send($message)) {
            if (Craft::$app->request->acceptsJson) {
                return $this->asJson(
                    [
                        'errors' => $formdata->getErrors(),
                        'message' => Craft::t('ctaform', 'form_error'),
                    ]
                );
            }
        }

        if (Craft::$app->request->acceptsJson) {
            return $this->asJson(
                [
                    'success' => true,
                    'message' => Craft::t('ctaform', 'form_sent'),
                ]
            );
        }
    }

    /**
     * Handle a request going to our plugin's actionDoSomething URL,
     * e.g.: actions/ctaform/default/do-something
     *
     * @return mixed
     */
    public function actionTest(): Response
    {
        $toEmails = Craft::parseEnv(Ctaform::$plugin->getSettings()->ctaEmail);
        $toEmails = is_string($toEmails) ? StringHelper::split($toEmails) : $toEmails;
        if (Craft::$app->request->acceptsJson) {
            return $this->asJson([
                'toEmail' => $toEmails,
            ]);
        }
    }
}
