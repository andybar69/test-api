<?php

namespace AppBundle\Controller\Api;

use AppBundle\Form\AuthorType;
use AppBundle\Controller\BaseController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Author;
use AppBundle\Form\UpdateAuthorType;
use AppBundle\Api\ApiProblem;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\Api\ApiProblemException;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;


class AuthorController extends BaseController
{
    /**
     * @Route("/api/authors")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            return $this->throwApiProblemValidationException($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($author);
        $em->flush();

        $response = $this->createApiResponse($author, 201);
        $authorUrl = $this->generateUrl(
            'api_authors_show',
            ['id' => $author->getId()]
        );
        $response->headers->set('Location', $authorUrl);

        return $response;
    }

    /**
     * @Route("/api/authors/{id}", name="api_authors_show")
     * @Method("GET")
     * @RateLimit(limit=100, period=60)
     */
    public function showAction($id)
    {
        $author = $this->getDoctrine()
            ->getManager()
            ->getRepository(Author::class)
            ->find($id);

        if (null === $author) {
            throw $this->createNotFoundException(sprintf('No author found with ID "%s"', $id));
        }

        $response = $this->createApiResponse($author, 200);

        return $response;
    }

    /**
     * @Route("/api/authors")
     * @Method("GET")
     */
    public function listAction()
    {
        $authors = $this->getDoctrine()
            ->getManager()
            ->getRepository(Author::class)
            ->findAll()
        ;

        $response = $this->createApiResponse(['authors' => $authors], 200);

        return $response;
    }

    /**
     * @Route("/api/authors/{id}")
     * @Method({"PUT", "PATCH"})
     */
    public function updateAction(Request $request, $id)
    {
        $author = $this->getDoctrine()
            ->getManager()
            ->getRepository(Author::class)
            ->find($id)
        ;
        if (null === $author) {
            throw $this->createNotFoundException(sprintf('No author found with ID "%s"', $id));
        }

        $form = $this->createForm(UpdateAuthorType::class, $author);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            return $this->throwApiProblemValidationException($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($author);
        $em->flush();

        $response = $this->createApiResponse($author, 200);

        return $response;
    }

    /**
     * @Route("/api/authors/{id}")
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        $author = $this->getDoctrine()
            ->getManager()
            ->getRepository(Author::class)
            ->find($id)
        ;

        if ($author) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($author);
            $em->flush();
        }

        return new JsonResponse(null, 204);
    }

    protected function serializeAuthor(Author $author)
    {
        return [
            'firstName' => $author->getFirstName(),
            'lastName' => $author->getLastName(),
            'nickname' => $author->getNickname(),
        ];
    }

    private function processForm(Request $request, FormInterface $form)
    {
        $data = $this->jsonDecode($request->getContent());
        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }


    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

    private function createValidationErrorResponse(FormInterface $form)
    {
        $errors = $this->getErrorsFromForm($form);
        $apiProblem = new ApiProblem(
            400,
            'validation_error'
        );
        $apiProblem->set('errors', $errors);
        $response = new JsonResponse($apiProblem->toArray(), $apiProblem->getStatusCode());
        $response->headers->set('Content-Type', 'application/problem+json');

        return $response;
    }

    private function throwApiProblemValidationException(FormInterface $form)
    {
        $errors = $this->getErrorsFromForm($form);
        $apiProblem = new ApiProblem(
            400,
            ApiProblem::TYPE_VALIDATION_ERROR
        );
        $apiProblem->set('errors', $errors);
        throw new ApiProblemException($apiProblem);
    }
}