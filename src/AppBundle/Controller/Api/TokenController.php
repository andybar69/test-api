<?php

namespace AppBundle\Controller\Api;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Api\ApiProblem;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\Api\ApiProblemException;


class TokenController extends BaseController
{
    /**
     * @Route("/api/authenticate")
     * @Method("POST")
     */
    public function newTokenAction(Request $request)
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->findOneBy(['username' => $data['username']]);

        $isValid = $this->get('security.password_encoder')->isPasswordValid($user, $data['password']);

        if (!$user || !$isValid) {
            $this->throwInvalidCredentialsException();
        }

        $tokenValue = $this->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 3600 // 1 hour expiration
            ]);

        $token = ['token' => $tokenValue];

        return $this->createApiResponse($token, 200);
    }


}