<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;
use AppBundle\Api\ApiProblemException;
use AppBundle\Api\ApiProblem;


class BaseController extends Controller
{
    protected function jsonDecode($var)
    {
        $data = json_decode($var, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);
            throw new ApiProblemException($apiProblem);
        }
        return $data;
    }

    protected function createApiResponse($data, $statusCode = 200)
    {
        $json = $this->serialize($data);
        return new Response($json, $statusCode, [
            'Content-Type' => 'application/json;charset=UTF-8'
        ]);
    }

    protected function serialize($data, $format = 'json')
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);

        $request = $this->get('request_stack')->getCurrentRequest();
        $groups = ['Default'];
        if ($request->query->get('deep')) {
            $groups[] = 'deep';
        }
        $context->setGroups($groups);

        return $this->container->get('jms_serializer')
            ->serialize($data, $format, $context);
    }

    protected function throwInvalidCredentialsException()
    {
        $apiProblem = new ApiProblem(
            401,
            ApiProblem::TYPE_INVALID_CREDENTIALS
        );
        //$apiProblem->set('errors', '');
        throw new ApiProblemException($apiProblem);
    }
}