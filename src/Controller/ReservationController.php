<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Salle;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;



#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/{id}', name: 'app_reservation', methods: ['GET'])]
    public function index($id, ReservationRepository $reservationRepository, ManagerRegistry $doctrine, TranslatorInterface $translator): Response
    {
        $salle = $doctrine->getRepository(Salle::class)->find($id);

        // Vérifier si la salle existe
        if (!$salle) {
            $message = $translator->trans('The room with this ID :'. $id .'does not exist.'); 

            $this->addFlash('error', $message);  
            return $this->redirectToRoute('app_salle');  
        }

        $events = $reservationRepository->findAll();
        $rdvs = [];
        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('reservation/index.html.twig', [
            'data' => $data,
            'users' => $doctrine->getRepository(User::class)->findAll(),
            'salle' => $salle,
        ]);
    }

    #[Route('/new/{id}', name: 'app_reservation_new', methods: ['GET','POST'])]
    public function new($id,Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $firstName = $form->get('firstName')->getData();
            $lastName = $form->get('lastName')->getData();
            $userRepository = $entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['firstname' => $firstName, 'lastname' => $lastName]);
        

            if ($user) {
                $reservation->setUser($user);
            } else {
                $errorMessage = $translator->trans('User not found');

                $this->addFlash('error', $errorMessage );
                return $this->redirectToRoute('app_reservation_new',['id' => $id]);
            }

            $entityManager->persist($reservation);
            $entityManager->flush();

            $successMessage = $translator->trans('Your reservation has been registered.') ;
            $this->addFlash('success', $successMessage);
            return $this->redirectToRoute('app_home');
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/handler', name: 'app_handler', methods: ['GET'])]
    public function showAll( ReservationRepository $reservationRepository, ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getRepository(User::class)->findAll();
        $events = $reservationRepository->findAll();
      

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('reservation/showAll.html.twig', [
            'events' => $events,
            'users' => $users,
   
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation', [], Response::HTTP_SEE_OTHER);
    }
}