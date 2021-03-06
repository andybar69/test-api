<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Author;
use AppBundle\Annotation\Link;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;


class LinkSerializationSubscriber implements EventSubscriberInterface
{
    private $router;
    private $annotationReader;
    private $expressionLanguage;

    public function __construct(RouterInterface $router, Reader $annotationReader)
    {
        $this->router = $router;
        $this->annotationReader = $annotationReader;
        $this->expressionLanguage = new ExpressionLanguage();
    }


//    public function onPostSerialize(ObjectEvent $event)
//    {
//        /** @var JsonSerializationVisitor $visitor */
//        $visitor = $event->getVisitor();
//
//        /** @var Author $author */
//        $author = $event->getObject();
//
//        $visitor->setData('uri',
//            $this->router->generate('api_authors_show', [
//                'id' => $author->getId()
//            ])
//        );
//    }

    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var JsonSerializationVisitor $visitor */
        $visitor = $event->getVisitor();
        $object = $event->getObject();
        $annotations = $this->annotationReader
            ->getClassAnnotations(new \ReflectionObject($object));
        $links = [];
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Link) {
                $uri = $this->router->generate(
                    $annotation->route,
                    $this->resolveParams($annotation->params, $object)
                );
                $links[$annotation->name] = $uri;
            }
        }
        if ($links) {
            $visitor->setData('_links', $links);
        }
    }

    private function resolveParams(array $params, $object)
    {
        foreach ($params as $key => $param) {
            $params[$key] = $this->expressionLanguage
                ->evaluate($param, array('object' => $object));
        }
        return $params;
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'format' => 'json',
                'class' => 'AppBundle\Entity\Author'
            ]
        ];
    }
}