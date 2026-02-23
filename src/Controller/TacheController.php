<?php

namespace App\Controller;

use App\Entity\Tache;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TacheController extends AbstractController
{
    #[Route('/taches', name: 'taches_liste')]
    public function liste(EntityManagerInterface $em): Response
    {
        $taches = $em->getRepository(Tache::class)->findBy([], ['terminee' => 'ASC']);
        return $this->render('tache/liste.html.twig', [
            'taches' => $taches,
        ]);
    }

    #[Route('/taches/ajouter', name: 'taches_ajouter')]
    public function ajouter(EntityManagerInterface $em): Response
    {
        $tache = new Tache();
        $tache->setTitre('Nouvelle tâche');
        $tache->setDescription('Description de la tâche');
        $tache->setTerminee(false);
        $tache->setDateCreation(new \DateTime());

        $em->persist($tache);
        $em->flush();

        return $this->redirectToRoute('taches_liste');
    }

    #[Route('/taches/{id}', name: 'taches_detail')]
    public function detail(Tache $tache): Response
    {
        return $this->render('tache/detail.html.twig', [
            'tache' => $tache,
        ]);
    }

    // Bonus : marquer une tâche comme terminée
    #[Route('/taches/{id}/terminer', name: 'taches_terminer')]
    public function terminer(Tache $tache, EntityManagerInterface $em): Response
    {
        $tache->setTerminee(true);
        $em->flush();
        return $this->redirectToRoute('taches_liste');
    }
}