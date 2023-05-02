<?php

namespace App\Controller;

use App\Entity\Pracownik;
use App\Entity\Zespol;
use App\Repository\ZespolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PracownikController extends AbstractController
{
    #[Route('/pracownik/dodajPracownika', name: 'dodajPracownika')]
    public function dodajPracownika(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pracownik = new Pracownik();
        $form = $this->utworzFormilarz($pracownik, false);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pracownik);
            $entityManager->flush();

            $wszycyPracownicy = $entityManager->getRepository(Pracownik::class)->findAll();
            return $this->render('pracownik/pracownicy.html.twig',
                [
                    'wszycyPracownicy' => $wszycyPracownicy
                ]);
        }

        return $this->render('pracownik/dodajPracownika.html.twig', [
            'form' => $form->createView(),
            'czyEdycja' => false
        ]);
    }

    private function utworzFormilarz(Pracownik $pracownik, $czyEdycja): FormInterface
    {
        $form = $this->createFormBuilder($pracownik)
            ->add('imie', TextType::class, array('label' => 'Imię',
                'attr' => array('class' => 'form-control')))
            ->add('nazwisko', TextType::class, array('label' => 'Nazwisko',
                'attr' => array('class' => 'form-control')))
            ->add('wynagrodzenie', NumberType::class, array('label' => 'Wynagrodzenie',
                'attr' => array('class' => 'form-control')))
            ->add('stanowisko', ChoiceType::class,
                array('choices' => array(
                    'Wybierz stanowisko' => null,
                    'Programista' => 'Programista',
                    'Analityk' => 'Analityk',
                    'Tester' => 'Tester',
                ),
                    'label' => 'Stanowisko',
                    'attr' => array('class' => 'form-control')))
            ->add('zespoId', EntityType::class,
                array('class' => Zespol::class,
                    'query_builder' => function (ZespolRepository $zespolRepository) {
                        return $zespolRepository->createQueryBuilder('z')
                            ->orderBy('z.nazwa', 'ASC');
                    },
                    'label' => 'Zespół',
                    'attr' => array('class' => 'form-control')))
            ->add('zapisz', SubmitType::class, array('label' => $czyEdycja ? 'Zapisz zmiany' : 'Dodaj pracownika',
                'attr' => array('class' => 'btn btn-outline-primary', 'style' => 'margin-top:8px')))
            ->getForm();

        return $form;
    }

    #[Route('/pracownik/edytujPracownika/{id}', name: 'edytujPracownika')]
    public function edytujPracownika(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $pracownik = $entityManager->getRepository(Pracownik::class)->find($id);
        $form = $this->utworzFormilarz($pracownik, true);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pracownik);
            $entityManager->flush();

            $wszycyPracownicy = $entityManager->getRepository(Pracownik::class)->findAll();
            return $this->render('pracownik/pracownicy.html.twig',
                [
                    'wszycyPracownicy' => $wszycyPracownicy
                ]);
        }

        return $this->render('zespol/dodajZespol.html.twig', [
            'form' => $form->createView(),
            'czyEdycja' => true
        ]);
    }
}
