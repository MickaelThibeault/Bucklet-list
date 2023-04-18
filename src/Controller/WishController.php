<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use App\Util\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/list", name="wish_list")
     */
    public function list(WishRepository $wishRepository) : Response {

        $listeSouhaits = $wishRepository->findPublishedWishesWithCategories();
        return $this->render('wish/list.html.twig', ['souhaits'=>$listeSouhaits]);
    }

    /**
     * @Route("/detail/{id}", name="wish_detail")
     */
    public function detail(int $id, WishRepository $wishRepository) : Response {

        $detail = $wishRepository->find($id);
        if (!$detail) throw $this->createNotFoundException("Ce souhait n'existe pas ! Désolé !");

        return $this->render('wish/detail.html.twig', ['souhait'=>$detail]);
    }

    /**
     * @Route("/addyours", name="wish_addyours")
     */
    public function addYours(Request $request,
                             EntityManagerInterface $entityManager,
                             Censurator $censurator
    ): Response
    {
        $souhait = new Wish();

        $currentUserUsername = $this->getUser()->getUserIdentifier();
        $souhait->setAuthor($currentUserUsername);

        $souhaitForm = $this->createForm(WishType::class, $souhait);

        $souhaitForm->handleRequest($request);

        if ($souhaitForm->isSubmitted() && $souhaitForm->isValid()) {
            $souhait->setIsPublished(true);
            $souhait->setDateCreated(new \DateTime());
            $souhait->setDescription($censurator->purify($souhait->getDescription()));

            $entityManager->persist($souhait);
            $entityManager->flush();

            $this->addFlash('success', 'Idea successfully added !');
            return $this->redirectToRoute('wish_detail', ['id'=>$souhait->getId()]);

        }

        return $this->render('wish/addyours.html.twig', [
            'souhaitForm'=>$souhaitForm->createView()
        ]);
    }

}