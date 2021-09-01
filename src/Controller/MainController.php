<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Entity\Team;
use App\Form\PersonneType;
use App\Repository\PersonneRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {

        $p = new Personne();

        $form = $this->createForm(PersonneType::class, $p);
        return $this->render('main/index.html.twig', [
            'form_p' => $form->createView(),
        ]);
    }

    /**
     * @Route("/main", name="main_lister")
     */
    public function listerEquipe(Request $req, TeamRepository $repo_teams, PersonneRepository $repo_per): Response
    {
        $res = $repo_teams->findAll();
        $per = $repo_per->findAll();


        return $this->render("main/index.html.twig",[
            "teams" => $res,
            "personnes" => $per
        ]);
    }

    /**
     * @Route("/addT", name="add_teams")
     */
    public function ajouterEquipe(Request $req, EntityManagerInterface $em): Response
    {

        $team = new Team();
        $team->setName($req->get('teamName'));
        $em->persist($team);
        $em->flush();

        return $this->redirectToRoute('main_lister');
    }

    /**
     * @Route("/addP", name="add_personne")
     */
    public function ajouterPersonne(Request $req, EntityManagerInterface $em, TeamRepository $repo_teams): Response
    {

        $team = $repo_teams->findOneBy(array('id'=>$req->get('team')));
        
        $per = new Personne();
        $per->setNom($req->get('name'));
        $per->setPrenom($req->get('prenom'));

        if($team){
            $per->addTeam($team);
        }
 
        $em->persist($per);
        $em->flush();

        return $this->redirectToRoute('main_lister');
    }

    /**
     * @Route("/revP/{personne}/{team}", name="remove_personne")
     */
    public function removePersonne(Personne $personne, Team $team, EntityManagerInterface $em): Response
    {

        $personne->removeTeam($team);
        $em->flush();
        
        return $this->redirectToRoute('main_lister');
    }

    /**
     * @Route("/delP/{id}", name="delete_personne")
     */
    public function deletePersonne(Personne $per, Request $req, EntityManagerInterface $em): Response
    {
        $em->remove($per);
        $em->flush();

        return $this->redirectToRoute('main_lister');
    }

    /**
     * @Route("/delT/{id}", name="delete_team")
     */
    public function deleteTeam(Team $per, Request $req, EntityManagerInterface $em): Response
    {

        $em->remove($per);
        $em->flush();

        return $this->redirectToRoute('main_lister');
    }

    /**
     * @Route("/upd/{personne}/{team}", name="upd_team")
     */
    public function modifteam(Personne $personne, Team $team, EntityManagerInterface $em): Response
    {

        $personne->addTeam($team);
        $em->flush();

        return $this->json("{'ok':true}");
    }
}
