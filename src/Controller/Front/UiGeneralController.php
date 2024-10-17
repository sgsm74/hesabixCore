<?php

namespace App\Controller\Front;

use App\Entity\APIDocument;
use App\Entity\BlogPost;
use App\Entity\Business;
use App\Entity\ChangeReport;
use App\Entity\HesabdariDoc;
use App\Entity\PrinterQueue;
use App\Entity\User;
use App\Entity\Settings;
use App\Service\pdfMGR;
use App\Service\Provider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use App\Service\SMS;
use Dompdf\Dompdf;

class UiGeneralController extends AbstractController
{
    #[Route('/', name: 'general_home')]
    public function general_home(SMS $sms, EntityManagerInterface $entityManager): Response
    {
        // $busCount = count($entityManager->getRepository(Business::class)->findAll());
        // $users = count($entityManager->getRepository(User::class)->findAll());
        // $docs = count($entityManager->getRepository(HesabdariDoc::class)->findAll());
        // $lastBusiness = $entityManager->getRepository(Business::class)->findLast();
        // if ($lastBusiness)
        //     return $this->render('general/home.html.twig', [
        //         'business' => $busCount + 11405,
        //         'users' => $users + 29471,
        //         'docs' => $docs + 105412,
        //         'lastBusinessName' => $lastBusiness->getname(),
        //         'lastBusinessOwner' => $lastBusiness->getOwner()->getFullName(),
        //         'blogPosts' => $entityManager->getRepository(BlogPost::class)->findBy([], ['dateSubmit' => 'DESC'], 3)
        //     ]);
        return $this->redirect('login');
        // return $this->render('general/home.html.twig', [
        //     'business' => $busCount + 11405,
        //     'users' => $users + 29471,
        //     'docs' => $docs + 105412,
        //     'lastBusinessName' => 'ثبت نشده',
        //     'lastBusinessOwner' => 'ثبت نشده',
        //     'blogPosts' => $entityManager->getRepository(BlogPost::class)->findBy([], ['dateSubmit' => 'DESC'], 3)
        // ]);
    }

}
