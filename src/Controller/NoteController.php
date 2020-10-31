<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     */
    public function index(NoteRepository $note): Response
    {
        $notes = $note->findAll();

        return $this->render('note/index.html.twig', compact('notes'));
    }

    /**
     * @Route("/create", name="app_note_create", methods="POST|GET")
     * 
     */
    public function create(Request $req, EntityManagerInterface $em): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($note);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('/note/create.html.twig', [
            'form' => $form->createView(),
            'note' => $note,
        ]);
    }
}
