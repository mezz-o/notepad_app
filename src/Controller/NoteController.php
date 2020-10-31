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

    /**
     * @Route("/edit/{id<[0-9]+>}", name="app_note_edit", methods="POST|GET")
     */
    public function edit(Note $note, EntityManagerInterface $em, Request $req): Response
    {
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute("app_home");
        }
        return $this->render("/note/edit.html.twig", ["form"=>$form->createView()]);
    }

    /**
     * @Route("/delete/{id<[0-9]+>}", name="app_note_delete", methods="DELETE|GET")
     */
    public function delete(Note $note, EntityManagerInterface $em): Response
    {
        $em->remove($note);
        $em->flush();

        return $this->redirectToRoute("app_home");
    }

}
