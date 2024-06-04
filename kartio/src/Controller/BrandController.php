<?php

namespace App\Controller;

use App\Document\Brand;
use App\Document\LoyaltyCard;
use App\Document\User;
use App\Form\BrandType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/brands")]
#[IsGranted("ROLE_ADMIN")]
class BrandController extends AbstractController
{
    #[Route("", name: "app_brands")]
    public function brands(DocumentManager $dm, UserInterface $user): Response
    {
        $brands = $dm->getRepository(Brand::class)->findAll();
        $accessibleBrands = [];
        $brandsAdminCount = [];

        foreach ($brands as $brand) {
            if ($brand->getUsers()->contains($user)) {
                $accessibleBrands[] = $brand;

                $adminCount = 0;
                foreach ($brand->getUsers() as $brandUser) {
                    if (in_array("ROLE_ADMIN", $brandUser->getRoles())) {
                        $adminCount++;
                    }
                }
                $brandsAdminCount[$brand->getId()] = $adminCount;
            }
        }

        return $this->render("brands/index.html.twig", [
            "brands" => $accessibleBrands,
            "brandsAdminCount" => $brandsAdminCount,
        ]);
    }

    #[Route("/brand/{id}", name: "app_brand")]
    public function brand(DocumentManager $dm, string $id, UserInterface $user): Response
    {
        $brand = $dm->getRepository(Brand::class)->find($id);

        if (!$brand->getUsers()->contains($user)) {
            throw $this->createAccessDeniedException("You do not have access to this brand.");
        }

        if (!$brand) {
            throw $this->createNotFoundException("Brand not found!");
        }

        return $this->render("brands/show.html.twig", [
            "brand" => $brand,
        ]);
    }


    #[Route("/create-test", name: "app_create_test")]
    public function createBrand(DocumentManager $dm): Response
    {
        $brand = new Brand("Kartio");

        $loyaltyCard1 = new LoyaltyCard("Test Testovič", "1234");
        $loyaltyCard2 = new LoyaltyCard("Kuba David", "5678");

        $brand->addLoyaltyCard($loyaltyCard1);
        $brand->addLoyaltyCard($loyaltyCard2);

        # Persist the brand and flush which will actually save it to the database.
        $dm->persist($brand);
        $dm->flush();

        return new Response("Brand created!");
    }

    #[Route("/new", name: "app_new_brand")]
    public function newBrand(Request $request, DocumentManager $dm, UserInterface $user): Response
    {
        $brand = new Brand("");
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brand->setName($form->get("name")->getData());
            $brand->addUser($user); // Associate the brand with the current user

            $dm->persist($brand);
            $dm->flush();

            return $this->redirectToRoute("app_brands");
        }

        return $this->render("brands/new.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    #[Route("/brand/{id}/invite", name: "app_brand_invite", methods: ["GET", "POST"])]
    public function invite(UserInterface $user, Brand $brand, Request $request, DocumentManager $dm): Response
    {
        if ($request->isMethod("POST")) {
            $email = $request->request->get("email");
            $invitee = $dm->getRepository(User::class)->findOneBy(["email" => $email]);

            if ($invitee) {
                if (!$brand->getUsers()->contains($invitee)) {
                    $brand->addUser($invitee);
                    $dm->flush();
                    $this->addFlash("success", "User invited successfully.");
                } else {
                    $this->addFlash("error", "User already has access.");
                }
            } else {
                $this->addFlash("error", "User not found.");
            }

            return $this->redirectToRoute("app_brand_invite", ["id" => $brand->getId()]);
        }

        return $this->render("brands/invite.html.twig", [
            "brand" => $brand,
        ]);
    }
}
