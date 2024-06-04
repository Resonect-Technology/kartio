<?php

namespace App\Controller;

use App\Document\Brand;
use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/customer")]
#[IsGranted("ROLE_USER")]
class UserController extends AbstractController
{
    private $security;
    private $logger;

    public function __construct(SecurityBundleSecurity $security, LoggerInterface $logger)
    {
        $this->security = $security;
        $this->logger = $logger;
    }

    #[Route("/loyalty-cards", name: "app_customer_loyalty_cards")]
    public function loyaltyCards(DocumentManager $dm, SecurityBundleSecurity $security): Response
    {
        $user = $security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('Logged in user is not of expected type.');
        }

        $email = $user->getEmail(); // This gets the email
        $this->logger->info('User email: ' . $email);

        $brands = $dm->getRepository(Brand::class)->findAll();
        $loyaltyCards = [];

        foreach ($brands as $brand) {
            $this->logger->info('Processing brand: ' . $brand->getName());
            foreach ($brand->getLoyaltyCards() as $card) {
                $this->logger->info('Checking card with email: ' . $card->getEmail());
                if ($card->getEmail() === $email) {
                    $this->logger->info('Found matching card for user');
                    $loyaltyCards[] = $card;
                }
            }
        }

        return $this->render("customer/loyalty_cards.html.twig", [
            "loyaltyCards" => $loyaltyCards,
        ]);
    }
}
