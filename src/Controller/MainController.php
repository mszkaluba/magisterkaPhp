<?php

namespace App\Controller;

use App\Entity\Pracownik;
use App\Entity\Zadanie;
use App\Entity\Zespol;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/wszytkieZespoly', name: 'wszytkieZespoly')]
    public function wszytkieZespoły(EntityManagerInterface $entityManager): Response
    {
        $wszytkieZespoly = $entityManager->getRepository(Zespol::class)->findAll();
        return $this->render('zespol/zespoly.html.twig',
        [
            'wszytkieZespoly' => $wszytkieZespoly
        ]);
    }

    #[Route('/wszycyPracownicy', name: 'wszycyPracownicy')]
    public function wszycyPracownicy(EntityManagerInterface $entityManager): Response
    {
        $wszycyPracownicy = $entityManager->getRepository(Pracownik::class)->findAll();
        return $this->render('pracownik/pracownicy.html.twig',
            [
                'wszycyPracownicy' => $wszycyPracownicy
            ]);
    }

    #[Route('/wszystkieZadania', name: 'wszystkieZadania')]
    public function wszystkieZadania(EntityManagerInterface $entityManager): Response
    {
        $wszystkieZadania = $entityManager->getRepository(Zadanie::class)->findAll();
        return $this->render('zadanie/zadania.html.twig',
            [
                'wszystkieZadania' => $wszystkieZadania
            ]);
    }
}
