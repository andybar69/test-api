<?php

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Api\ApiProblem;
use AppBundle\Api\ApiProblemException;


class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $ex = $event->getException();
        if ($ex instanceof ApiProblemException) {
            $apiProblem = $ex->getApiProblem();
        }
        else {
            $statusCode = $ex instanceof HttpExceptionInterface ? $ex->getStatusCode() : 500;
            $apiProblem = new ApiProblem($statusCode);

            if ($ex instanceof HttpExceptionInterface) {
                $apiProblem->set('detail', $ex->getMessage());
            }
        }

        $response = new JsonResponse(
            $apiProblem->toArray(),
            $apiProblem->getStatusCode()
        );
        $response->headers->set('Content-Type', 'application/problem+json');

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }
}