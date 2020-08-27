<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Campaign;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Participant;
use App\Entity\Payment;
/**
 * @Route("/payment")
 */
class PaymentController extends AbstractController
{
    /**
     * @Route("/new/{id}", name="payment_new")
     */
    public function form(Campaign $campaign)
    {
        return $this->render('payment/new.html.twig', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * @Route("/save/{id}", name="payment_save", methods={"GET","POST"})
     */
    public function save(Request $request, Campaign $campaign): Response
    {

        $participant = new Participant();
        $participant->setName($request->request->get('name'));
        $participant->setEmail($request->request->get('email'));
        $participant->setCampaign($campaign);

        $payment = new Payment();
        $payment->setAmount($request->request->get('amount'));
        $payment->setParticipant($participant);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($participant);
        $entityManager->persist($payment);
        $entityManager->flush();
        
        return $this->redirectToRoute('campaign_show', ['id' => $campaign->getId()]);
    }
}
