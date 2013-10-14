<?php

namespace Fp\JsErrorNotifierBundle\Controller;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Fp\BadaBoomBundle\ExceptionCatcher\ExceptionCatcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NotifierController extends Controller
{
    protected $email_suject = 'Javascript error notofication';
    protected $email_text = '
Javascript error notofication:

Agent: %1$s
Page: %2$s
File: %3$s
Line: %4$s
Message: %5$s';

    public function sendNotifyAction(Request $request)
    {
        $domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'jsnotification.com';

        $text = sprintf($this->email_text,
            $request->get('userAgent'),
            $request->get('url'),
            $request->get('file'),
            $request->get('line'),
            $request->get('msg')
        );

        /** @var ExceptionCatcher $badaboom */
        $badaboom = null;//$this->get('fp_badaboom.exception_catcher');
        $emails = (array) $this->container->getParameter('fp_js_error_notifier.emails');

        if (null !== $badaboom) {
            $e = new \Exception($text);
            $badaboom->handleException($e);
        } elseif (!empty($emails)) {
            $message = \Swift_Message::newInstance()
                 ->setSubject($this->email_suject)
                 ->setFrom('noreply@'.$domain)
                 ->setTo($emails)
                 ->setBody($text);
            $this->get('mailer')->send($message);
        } else {
            throw new \Exception('Can not send the notification to a specified email. You have to configure FpJsErrorNotifier bundle or enable BadaBoom bundle');
        }

        return new JsonResponse();
    }
}
