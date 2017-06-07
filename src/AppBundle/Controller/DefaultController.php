<?php

namespace AppBundle\Controller;

use Admin\ArticleBundle\Entity\Article;
use Admin\ArticleBundle\Entity\Commentaire;
use Admin\ArticleBundle\Form\ArticleType;
use Admin\ArticleBundle\Form\CommentaireType;
use FOS\UserBundle\Model\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $user = $this->getUser();
        if ( $this->isGranted('ROLE_ADMIN'))  {
            return $this->redirect($this->generateUrl('gestiondusite'));
        }
        return $this->render('index.html.twig', array(
            'articles' => $articles,
            'user' => $user
        ));
    }
    /**
     * @Route("/dev", name="homepage_dev")
     */
    public function indexDevAction(Request $request)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $user = $this->getUser();

        return $this->render('index.html.twig', array(
            'articles' => $articles,
            'user' => $user
        ));
    }
    /**
     * @Route("/admin", name="gestiondusite")
     */
    public function gestionnaireAction() {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $user = $this->getUser();
        return $this->render('AdminArticleBundle:Admin:backoffice.html.twig', array(
            'articles' => $articles,
            'user' => $user
        ));
    }
    /**
     * @Route("/admin/all", name="allArticles")
     */
    public function showAllArticleAction(){
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('AdminArticleBundle:Admin:allArticles.html.twig', array(
            "articles" => $articles
        ));
    }
    /**
     * @Route("/admin/update/{id}", name="updateArticles")
     */
    public function updateArticleAction($id, Request $request) {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirect($this->generateUrl('gestiondusite'));
        }
        return $this->render('AdminArticleBundle:Admin:viewArticle.html.twig', array(
            'article' => $article,
            'form' => $form->createView()
        ));
    }
    /**
     * @Route("/admin/new", name="addArticles")
     */
    public function addArticleAction(Request $request)
    {
        $article = new Article();
        $user = $this->getUser();
        $form = $this->createForm(ArticleType::class, $article);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $file = $article->getFile();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('image_directory'),
                $fileName
            );
            $article->setImage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('AdminArticleBundle:Admin:formAdd.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }
    /**
     * @Route("/admin/deleteA/{id}", name="deleteArticles")
     */
    public function deleteArticleAction($id)
    {
        $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('article'=>    $id));
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        foreach ($commentaire as $com) {
            $em->remove($com);
        }
        $em->remove($article);
        $em->flush();
        return $this->redirect($this->generateUrl('homepage'));

    }
    /**
     * @Route("/articleView/{id}", name="viewshit")
     */
    public function articleViewAction($id, Request $request)
    {
        $user = $this->getUser();
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $comment = $this->getDoctrine()->getRepository(Commentaire::class)->findby(array('article' => $id));
        $commentaire = new Commentaire();
        $formCom = $this->createForm(CommentaireType::class, $commentaire);
        $formCom->add('commenter' , SubmitType::class);
        $formCom->handleRequest($request);
        if ($formCom->isValid()) {
            $commentaire->setArticle($article);
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            return $this->redirect($this->generateUrl('viewshit', array(
                'id' => $article->getId()
            )));
        }

        return $this->render('AdminArticleBundle:Default:articleView.html.twig', array(
            'article' => $article,
            'comment' => $comment,
            'formCom' => $formCom->createView(),
            'user' => $user
        ));
    }


}
