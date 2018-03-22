<?php

namespace AppBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateAuthorType extends AuthorType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        // override this!
        $resolver->setDefaults(['is_edit' => true]);
    }
    public function getName()
    {
        return 'author_edit';
    }
}