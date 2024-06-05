<?php

namespace App\Controller;

use App\Document\Brand;
use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

# This controller is responsible for handling user-related actions
# such as viewing loyalty cards.

#[Route("/customer")]
#[IsGranted("ROLE_USER")]
class UserController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    # This method is responsible for rendering the list of loyalty cards
    #[Route("/loyalty-cards", name: "app_customer_loyalty_cards")]
    public function loyaltyCards(DocumentManager $dm, SecurityBundleSecurity $security): Response
    {
        $user = $security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException("Přihlášený uživatel nemá platný formát.");
        }

        $email = $user->getEmail(); // This gets the email
        $this->logger->info("User email: " . $email);

        $brands = $dm->getRepository(Brand::class)->findAll();
        $loyaltyCards = [];

        # Loop through all brands and their loyalty cards
        foreach ($brands as $brand) {
            $this->logger->info("Processing brand: " . $brand->getName());
            foreach ($brand->getLoyaltyCards() as $card) {
                $this->logger->info("Checking card with email: " . $card->getEmail());
                if ($card->getEmail() === $email) {
                    $this->logger->info("Found matching card for user");
                    $loyaltyCards[] = $card;
                }
            }
        }

        return $this->render("customer/loyalty_cards.html.twig", [
            "loyaltyCards" => $loyaltyCards,
        ]);
    }
}
