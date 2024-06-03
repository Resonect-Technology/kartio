<?php

namespace App\Controller;

use App\Document\Brand;
use App\Document\LoyaltyCard;
use App\Form\BrandType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/brands")]
class BrandController extends AbstractController
{
    #[Route("", name: "app_brands")]
    public function brands(DocumentManager $dm): Response
    {
        $brands = $dm->getRepository(Brand::class)->findAll();

        return $this->render("brands/index.html.twig", [
            "brands" => $brands,
        ]);
    }

    #[Route("/brand/{id}", name: "app_brand")]
    public function brand(DocumentManager $dm, string $id): Response
    {
        $brand = $dm->getRepository(Brand::class)->find($id);

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
    public function newBrand(Request $request, DocumentManager $dm): Response
    {
        $brand = new Brand("");

        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->persist($brand);
            $dm->flush();

            return $this->redirectToRoute("app_brands");
        }

        return $this->render("brands/new.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}
