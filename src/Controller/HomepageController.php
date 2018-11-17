<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="app_homepage", methods={"GET", "POST"})
     */
    public function indexAction(Request $request): Response
    {
        $data = [];
        $results = null;

        $form = $this->getSearchForm($data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $results =
        }

        return $this->render('homepage/index.html.twig', [
            'form' => $form->createView(),
            'results' => $results,
        ]);
    }

    private function getSearchForm(array $data)
    {
        return $this->createFormBuilder($data)
            ->setMethod(Request::METHOD_POST)
            ->add('search', TextType::class)
            ->add('save', SubmitType::class)
            ->getForm()
        ;
    }
}
