<?php

namespace Fp\JsErrorNotifierBundle\Controller;

use Fp\BadaBoomBundle\ExceptionCatcher\ExceptionCatcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
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
        $text = sprintf($this->email_text,
            $request->get('userAgent'),
            $request->get('url'),
            $request->get('file'),
            $request->get('line'),
            $request->get('msg')
        );

        try {
            /** @var ExceptionCatcher $badaboom */
            $badaboom = $this->get('fp_badaboom.exception_catcher');
            $e = new \Exception($text);
            $badaboom->handleException($e);
        } catch (ServiceNotFoundException $e) {
            $from = $this->container->getParameter('fp_js_error_notifier.email_from');
            $to = (array) $this->container->getParameter('fp_js_error_notifier.email_to');

            if (empty($to)) {
                throw new \Exception('Can not send the notification to a specified email. You have to configure FpJsErrorNotifier bundle or enable BadaBoom bundle');
            }

            $message = \Swift_Message::newInstance()
                ->setSubject($this->email_suject)
                ->setFrom($from)
                ->setTo($to)
                ->setBody($text);
            $this->get('mailer')->send($message);
        }

        return new JsonResponse();
    }
}
