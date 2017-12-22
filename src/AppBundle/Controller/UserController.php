<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserEditForm;
use AppBundle\Form\UserRegistrationForm;
use AppBundle\Form\UserScientistForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(UserRegistrationForm::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Welcome '.$user->getEmail());

            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/{id}", name="user_show")
     */
    public function showAction(User $user)
    {
        return $this->render('user/show.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request)
    {
        $form = $this->createForm(UserScientistForm::class, $user);
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isValid()) {
//            $formData = $form->getNormData();
//            $genuses = $formData->getStudiedGenuses();
//            $genusesAll = $this->getDoctrine()
//                ->getRepository('AppBundle:Genus')
//                ->findAll();
//            foreach ($genusesAll as $genus){
//                if(!$genuses->contains($genus)){
//                    $genus->removeGenusScientist($user);
//                }else {
//                    $genus->addGenusScientist($user);
//                }
//                $em->persist($genus);
//            }
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'User Updated!');

            return $this->redirectToRoute('user_edit', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'userForm' => $form->createView()
        ]);
    }
}
