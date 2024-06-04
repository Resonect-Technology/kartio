<?php

namespace App\Controller;

use App\Document\User;
use App\Form\RegistrationType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route("/register", name: "app_register", methods: ["GET", "POST"])]
    public function register(Request $request, DocumentManager $dm, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            // Ensure email is set from form data
            $email = $form->get("email")->getData();
            $user->setEmail($email);

            // Assign roles based on the select value
            $role = $form->get("role")->getData();
            $user->setRoles([$role]);

            // Persist the user to the database
            $dm->persist($user);
            $dm->flush();

            // Add a flash message for successful registration
            $this->addFlash("success", "Registrace byla úspěšná.");

            // Redirect to the login page
            return $this->redirectToRoute("app_login");
        }

        return $this->render("security/register.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    #[Route("/login", name: "app_login", methods: ["GET", "POST"])]
    public function login(AuthenticationUtils $authUtils): Response
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        if ($error) {
            $this->addFlash("error", "Přihlášení selhalo, prosím zkuste to znovu.");
        }

        return $this->render("security/login.html.twig", [
            "last_username" => $lastUsername,
            "error" => $error,
        ]);
    }

    #[Route("/logout", name: "app_logout", methods: ["GET", "POST"])]
    public function logout(): void
    {
        throw new \Exception("This should never be reached!");
    }
}
