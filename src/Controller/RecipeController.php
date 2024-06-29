<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\User;
use App\Form\Type\RecipeType;
use App\Service\RecipeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\CategoryServiceInterface;
use App\Service\TagServiceInterface;
use App\Repository\RecipeRepository;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RecipeController.
 */
#[Route('/recipe')]
class RecipeController extends AbstractController
{
    private CategoryServiceInterface $categoryService;
    private TagServiceInterface $tagService;
    private RecipeServiceInterface $recipeService;
    private TranslatorInterface $translator;

    /**
     * @param CategoryServiceInterface $categoryService
     * @param TagServiceInterface $tagService
     * @param RecipeServiceInterface $recipeService
     * @param TranslatorInterface $translator
     */
    public function __construct(
        CategoryServiceInterface $categoryService,
        TagServiceInterface $tagService,
        RecipeServiceInterface $recipeService,
        TranslatorInterface $translator,
    ) {
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
        $this->recipeService = $recipeService;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route(name: 'recipe_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        $categoryId = $request->query->get('categoryId');
        $tagId = $request->query->get('tagId');

        $categoryId = ctype_digit($categoryId) ? (int) $categoryId : null;
        $tagId = ctype_digit($tagId) ? (int) $tagId : null;

        $categories = $this->categoryService->findAll();
        $tags = $this->tagService->findAll();

        if ($this->isGranted('ROLE_ADMIN') || $this->getUser() == null) {
            $pagination = $this->recipeService->getAllPaginatedList(1, $categoryId, $tagId);
        } else {
            $pagination = $this->recipeService->getPaginatedList(1, $user, $categoryId, $tagId);
        }

        return $this->render('recipe/index.html.twig', [
            'pagination' => $pagination,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * @param Recipe $recipe
     * @return Response
     */
    #[Route('/{id}', name: 'recipe_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    #[IsGranted('VIEW', subject: 'recipe')]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', ['recipe' => $recipe]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/create', name: 'recipe_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->addFlash('error', 'message.access_denied');
            return $this->redirectToRoute('app_login');
        }

        $recipe = new Recipe();
        $recipe->setAuthor($user);
        $form = $this->createForm(RecipeType::class, $recipe, ['action' => $this->generateUrl('recipe_create')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->save($recipe);

            $this->addFlash('success', $this->translator->trans('message.created_successfully'));
            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param Recipe $recipe
     * @return Response
     */
    #[Route('/{id}/edit', name: 'recipe_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('VIEW', subject: 'recipe')]
    public function edit(Request $request, Recipe $recipe): Response
    {
        if ($recipe->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('warning', $this->translator->trans('message.record_not_found'));
            return $this->redirectToRoute('recipe_index');
        }

        $form = $this->createForm(RecipeType::class, $recipe, [
            'method' => 'PUT',
            'action' => $this->generateUrl('recipe_edit', ['id' => $recipe->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->save($recipe);

            $this->addFlash('success', $this->translator->trans('message.edited_successfully'));
            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/edit.html.twig', ['form' => $form->createView(), 'recipe' => $recipe]);
    }

    /**
     * @param Request $request
     * @param Recipe $recipe
     * @return Response
     */
    #[Route('/{id}/delete', name: 'recipe_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('VIEW', subject: 'recipe')]
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($recipe->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('warning', $this->translator->trans('message.record_not_found'));
            return $this->redirectToRoute('recipe_index');
        }

        $form = $this->createForm(FormType::class, $recipe, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('recipe_delete', ['id' => $recipe->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->delete($recipe);

            $this->addFlash('success', $this->translator->trans('message.deleted_successfully'));
            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/delete.html.twig', ['form' => $form->createView(), 'recipe' => $recipe]);
    }

    /**
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    #[Route('/top-rated', name: 'recipe_top-rated')]
    public function topRated(RecipeRepository $recipeRepository): Response
    {
        $topRatedRecipes = $recipeRepository->findBy([], ['id' => 'DESC'], 10);

        return $this->render('recipe/top_rated.html.twig', [
            'recipes' => $topRatedRecipes,
        ]);
    }

}

