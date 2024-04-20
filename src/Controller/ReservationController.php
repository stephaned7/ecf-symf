<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Salle;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/{id}', name: 'app_reservation', methods: ['GET'])]
    public function index($id, ReservationRepository $reservationRepository, ManagerRegistry $doctrine, Security $security): Response
    {
        if(!$security->isGranted('IS_NOT_BANNED')){
            return $this->redirectToRoute('app_home');
        }

        $salle = $doctrine->getRepository(Salle::class)->find($id);

        // Vérifier si la salle existe
        if (!$salle) {
            $this->addFlash('error', 'La salle avec l\'ID ' . $id . ' n\'existe pas.');  // Affiche un message d'erreur
            return $this->redirectToRoute('app_salle');  // Redirige vers une route par défaut ou une page d'erreur
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
    public function new(int $id, Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, Security $security): Response
    {

        if(!$security->isGranted('IS_NOT_BANNED')){
            return $this->redirectToRoute('app_home');
        }
        // Récupérer l'objet Salle correspondant à partir de l'ID
        $salle = $doctrine->getRepository(Salle::class)->find($id);

        // Vérifier si la salle existe
        if (!$salle) {
            throw $this->createNotFoundException('La salle avec l\'ID '.$salle.' n\'existe pas.');
        }

        $reservation = new Reservation();
        $reservation->setSalle($salle);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        // Traiter le formulaire lorsqu'il est soumis
        if ($form->isSubmitted() && $form->isValid()) {
            //Définition de la durée de réservation
            $start = $reservation->getStart();
            $end = $reservation->getEnd();
            $interval = $start->diff($end);
            $hours = $interval->h + $interval->days * 24;
            if($hours < 1 || $hours > 4) {
                $this->addFlash('error', 'La durée de réservation doit être entre 1h minimum et 4h maximum.');
                return $this->redirectToRoute('app_reservation_new', ['id' => $id]);
            }

            //Vérifier si une reservation existe deja sur ce créneaux
            $conflictingReservations = $doctrine->getRepository(Reservation::class)->findByConflictingReservations($salle, $start, $end);
            
            if(!empty($conflictingReservations)){
                $this->addFlash('error', 'Il y a déjà une réservation pour ce créneau.');
                return $this->redirectToRoute('app_reservation_new', ['id' => $id]);
            }

            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'La réservation a bien été enregitrée.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
            'salle' =>  $salle
        ]);
    }
    #[Route('/handler', name: 'app_handler', methods: ['GET'])]
    public function showAll( ReservationRepository $reservationRepository, ManagerRegistry $doctrine, Security $security): Response
    {
        $users = $doctrine->getRepository(User::class)->findAll();
        $events = $reservationRepository->findAll();
      
        if(!$security->isGranted('IS_NOT_BANNED')){
            return $this->redirectToRoute('app_home');
        }

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