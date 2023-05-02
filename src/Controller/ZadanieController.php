<?php

namespace App\Controller;

use App\Entity\Pracownik;
use App\Entity\Zadanie;
use App\Repository\PracownikRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ZadanieController extends AbstractController
{
    #[Route('/zadanie/dodajZadanie', name: 'dodajZadanie')]
    public function dodajZadanie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $zadanie = new Zadanie();
        $form = $this->utworzFormilarz($zadanie, false);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($zadanie);
            $entityManager->flush();

            $wszystkieZadania = $entityManager->getRepository(Zadanie::class)->findAll();
            return $this->render('zadanie/zadania.html.twig',
                [
                    'wszystkieZadania' => $wszystkieZadania
                ]);
        }

        return $this->render('zadanie/dodajZadanie.html.twig', [
            'form' => $form->createView(),
            'czyEdycja' => false
        ]);
    }

    private function utworzFormilarz(Zadanie $zadanie, $czyEdycja): FormInterface
    {
        $form = $this->createFormBuilder($zadanie)
            ->add('nazwa', TextType::class, array('label' => 'Nazwa',
                'attr' => array('class' => 'form-control')))
            ->add('opis', TextareaType::class, array('label' => 'Opis zadania',
                'attr' => array('class' => 'form-control')))
            ->add('status', ChoiceType::class,
                array('choices' => array(
                    'Wybierz status' => null,
                    'Do zrobienia' => 'Do zrobienia',
                    'W trakcie implementacji' => 'W trakcie implementacji',
                    'Podczas testów' => 'Podczas testów',
                    'W trakcie poprawek' => 'W trakcie poprawek',
                    'Gotowe' => 'Gotowe'
                ),
                    'label' => 'Status zadania',
                    'attr' => array('class' => 'form-control')))
            ->add('pracownikId', EntityType::class,
                array('class' => Pracownik::class,
                    'query_builder' => function (PracownikRepository $pracownikRepository) {
                        return $pracownikRepository->createQueryBuilder('p')
                            ->orderBy('p.nazwisko', 'ASC');
                    },
                    'label' => 'Zespół',
                    'attr' => array('class' => 'form-control')))
            ->add('zapisz', SubmitType::class, array('label' => $czyEdycja ? 'Zapisz zmiany' : 'Dodaj zadanie',
                'attr' => array('class' => 'btn btn-outline-primary', 'style' => 'margin-top:8px')))
            ->getForm();

        return $form;
    }

    #[Route('/zadanie/edytujZadanie/{id}', name: 'edytujZadanie')]
    public function edytujZadanie(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $zadanie = $entityManager->getRepository(Zadanie::class)->find($id);
        $form = $this->utworzFormilarz($zadanie, true);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($zadanie);
            $entityManager->flush();

            $wszystkieZadania = $entityManager->getRepository(Zadanie::class)->findAll();
            return $this->render('zadanie/zadania.html.twig',
                [
                    'wszystkieZadania' => $wszystkieZadania
                ]);
        }

        return $this->render('zadanie/dodajZadanie.html.twig', [
            'form' => $form->createView(),
            'czyEdycja' => true
        ]);
    }
}
