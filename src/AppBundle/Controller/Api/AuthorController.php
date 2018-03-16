<?php

namespace AppBundle\Controller\Api;

use AppBundle\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Author;


class AuthorController extends Controller
{
    /**
     * @Route("/api/authors")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $response->setData(['message' => json_last_error_msg()])->setStatusCode(400);
        }
        try {
            $author = new Author();
            $form = $this->createForm(AuthorType::class, $author);
            $form->submit($data);
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            $data = $this->serializeAuthor($author);
            $authorUrl = $this->generateUrl('api_authors_show', ['id' => $author->getId()]);
            $response
                ->setData($data)
                ->setStatusCode(201)
                ->headers->set('Location', $authorUrl);
        }
        catch (\Exception $ex) {
            $response->setData(['message' => $ex->getMessage()])->setStatusCode(500);
        }

        return $response;
    }

    /**
     * @Route("/api/authors/{id}", name="api_authors_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $response = new JsonResponse();

        $author = $this->getDoctrine()
            ->getManager()
            ->getRepository(Author::class)
            ->find($id)
        ;

        if (null === $author) {
            throw $this->createNotFoundException(sprintf('No author found with ID "%s"', $id));
        }

        $data = $this->serializeAuthor($author);

        return $response->setData($data)->setStatusCode(200);
    }

    /**
     * @Route("/api/authors")
     * @Method("GET")
     */
    public function listAction()
    {
        $response = new JsonResponse();

        $authors = $this->getDoctrine()
            ->getManager()
            ->getRepository(Author::class)
            ->findAll()
        ;
        $data = ['authors' => []];
        foreach ($authors as $author) {
            $data['authors'][] = $this->serializeAuthor($author);
        }

        return $response->setData($data)->setStatusCode(200);
    }

    protected function serializeAuthor(Author $author)
    {
        return [
            'firstName' => $author->getFirstName(),
            'lastName' => $author->getLastName(),
        ];
    }
}