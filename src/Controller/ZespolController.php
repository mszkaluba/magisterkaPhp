<?php

namespace App\Controller;

use App\Entity\Pracownik;
use App\Entity\Zespol;
use App\Repository\PracownikRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ZespolController extends AbstractController
{
    #[Route('/zespol/dodajZespol', name: 'dodajZespol')]
    public function dodajZespol(Request $request, EntityManagerInterface $entityManager): Response
    {
        $zespol = new Zespol();
        $form = $this->utworzFormilarz($zespol, false);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($zespol);
            $szefZespolu = $zespol->getSzefZespolu();
            $szefZespolu->setZespoId($zespol);
            $entityManager->persist($szefZespolu);
            $entityManager->flush();

            $wszytkieZespoly = $entityManager->getRepository(Zespol::class)->findAll();
            return $this->render('zespol/zespoly.html.twig',
                [
                    'wszytkieZespoly' => $wszytkieZespoly
                ]);
        }

        return $this->render('zespol/dodajZespol.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function utworzFormilarz(Zespol $zespol, $czyEdycja): FormInterface
    {
        $form = $this->createFormBuilder($zespol)
            ->add('nazwa', TextType::class, array('label' => 'Nazwa',
                'attr' => array('class' => 'form-control')))
            ->add('maksymalnaIloscPracownikow', NumberType::class, array('label' => 'Wielkość zespołu',
                'attr' => array('class' => 'form-control')))
            ->add('szefZespolu', EntityType::class,
                array('class' => Pracownik::class,
                    'query_builder' => function (PracownikRepository $pracownikRepository) {
                        return $pracownikRepository->createQueryBuilder('p')
                            ->orderBy('p.nazwisko', 'ASC');
                    },
                    'label' => 'Szef zespołu',
                    'attr' => array('class' => 'form-control')))
            ->add('zapisz', SubmitType::class, array('label' => $czyEdycja ? 'Zapisz zmiany' : 'Dodaj zespół',
                'attr' => array('class' => 'btn btn-outline-primary', 'style' => 'margin-top:8px')))
            ->getForm();

        return $form;
    }

    #[Route('/zespol/edytujZespol/{id}', name: 'edytujZespol')]
    public function edytujZespol(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $zespol = $entityManager->getRepository(Zespol::class)->find($id);
        $szefZespoluPrzedEdycja = $zespol->getSzefZespolu();
        $form = $this->utworzFormilarz($zespol, true);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($zespol);
            if ($szefZespoluPrzedEdycja != $zespol->getSzefZespolu()) {
                $nowySzefZespolu = $zespol->getSzefZespolu();
                $nowySzefZespolu->setZespoId($zespol);
                $entityManager->persist($nowySzefZespolu);
            }
            $entityManager->flush();

            $wszytkieZespoly = $entityManager->getRepository(Zespol::class)->findAll();
            return $this->render('zespol/zespoly.html.twig',
                [
                    'wszytkieZespoly' => $wszytkieZespoly
                ]);
        }

        return $this->render('zespol/dodajZespol.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
