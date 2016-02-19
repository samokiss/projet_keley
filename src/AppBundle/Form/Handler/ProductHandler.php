<?php

namespace AppBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductHandler
{
    protected $requestStack;
    protected $om;

    public function __construct(RequestStack $requestStack, ObjectManager $om)
    {
        $this->requestStack = $requestStack;
        $this->om = $om;
    }

    /**
     * @param FormInterface $form
     *
     * @return bool
     */
    public function process(FormInterface $form)
    {
        $request = $this->requestStack->getCurrentRequest();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->doOnSuccess($form);

            return true;
        }

        return false;
    }

    protected function doOnSuccess(FormInterface $form)
    {
        $product = $form->getData();
        $tags = $form->get('newTags')->getData();

        foreach ($tags as $tag) {
            /** @var Tag $tag */
            $product->addTag($tag);
        }

        $this->om->persist($product);
        $this->om->flush();
    }
}