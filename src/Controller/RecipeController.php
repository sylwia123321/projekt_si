<?php
/**
 * Recipe controller.
 */

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\Type\RecipeType;
use App\Service\RecipeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use App\Form\Type\RatingType;
use App\Repository\RecipeRepository;

/**
 * Class RecipeController.
 */
#[\Symfony\Component\Routing\Attribute\Route('/recipe')]
class RecipeController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param CategoryRepository     $categoryRepository Category repository
     * @param TagRepository          $tagRepository      Tag repository
     * @param RecipeServiceInterface $recipeService      Recipe service
     * @param TranslatorInterface    $translator         Translator
     * @param RecipeRepository       $recipeRepository   Recipe repository
     */
    public function __construct(private readonly CategoryRepository $categoryRepository, private readonly TagRepository $tagRepository, private readonly RecipeServiceInterface $recipeService, private readonly TranslatorInterface $translator, private readonly RecipeRepository $recipeRepository)
    {
    }

    /**
     * Index action.
     *
     * @param Request $request Request
     *
     * @return Response response
     */
    #[\Symfony\Component\Routing\Attribute\Route(name: 'recipe_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $user = $this->getUser();

        $categoryId = $request->query->get('categoryId');
        $tagId = $request->query->get('tagId');

        $categoryId = ctype_digit($categoryId) ? (int) $categoryId : null;
        $tagId = ctype_digit($tagId) ? (int) $tagId : null;

        $categories = $this->categoryRepository->findAll();
        $tags = $this->tagRepository->findAll();

        if ($this->isGranted('ROLE_ADMIN') || !$this->getUser() instanceof \Symfony\Component\Security\Core\User\UserInterface) {
            $pagination = $this->recipeService->getAllPaginatedList($page, $categoryId, $tagId);
        } else {
            $pagination = $this->recipeService->getPaginatedList($page, $user, $categoryId, $tagId);
        }

        return $this->render('recipe/index.html.twig', ['pagination' => $pagination, 'categories' => $categories, 'tags' => $tags]);
    }

    /**
     * Show action.
     *
     * @param Recipe $recipe Recipe entity
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}', name: 'recipe_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    #[IsGranted('VIEW', subject: 'recipe')]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', ['recipe' => $recipe]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/create', name: 'recipe_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash(
                'danger',
                $this->translator->trans('message.access_denied')
            );

            return $this->redirectToRoute('recipe_index');
        }
        $recipe = new Recipe();
        $form = $this->createForm(
            RecipeType::class,
            $recipe,
            ['action' => $this->generateUrl('recipe_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->save($recipe);

            $this->addFlash('success', $this->translator->trans('message.created_successfully'));

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Recipe  $recipe  Recipe entity
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}/edit', name: 'recipe_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('IS_AUTHENTICATED_FULLY', subject: 'recipe')]
    public function edit(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe, ['method' => 'PUT', 'action' => $this->generateUrl('recipe_edit', ['id' => $recipe->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->save($recipe);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/edit.html.twig', ['form' => $form->createView(), 'recipe' => $recipe]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Recipe  $recipe  Recipe entity
     *
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}/delete', name: 'recipe_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('VIEW', subject: 'recipe')]
    public function delete(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(FormType::class, $recipe, ['method' => 'DELETE', 'action' => $this->generateUrl('recipe_delete', ['id' => $recipe->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->delete($recipe);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/delete.html.twig', ['form' => $form->createView(), 'recipe' => $recipe]);
    }

    /**
     * Rate action.
     *
     * @param Request          $request          Request
     * @param Recipe           $recipe           Recipe
     * @param RecipeRepository $recipeRepository Recipe repository
     *
     * @return Response response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}/rate', name: 'recipe_rate', requirements: ['id' => '[1-9]\d*'], methods: 'GET|POST')]
    public function rate(Request $request, Recipe $recipe, RecipeRepository $recipeRepository): Response
    {
        $form = $this->createForm(RatingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ratingValue = $form->get('rating')->getData();

            $currentRating = $recipe->getRating() ?? 0;
            $ratingCount = $recipe->getRatingCount() ?? 0;

            $newRating = (($currentRating * $ratingCount) + $ratingValue) / ($ratingCount + 1);
            $recipe->setRating($newRating);
            $recipe->setRatingCount($ratingCount + 1);

            $recipeRepository->add($recipe, true);

            return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('recipe/rate.html.twig', ['recipe' => $recipe, 'form' => $form->createView()]);
    }

    /**
     * Top-rated.
     *
     * @return Response response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/top-rated', name: 'recipe_top-rated')]
    public function topRated()
    {
        $recipes = $this->recipeRepository->findTopRated();

        return $this->render('recipe/top_rated.html.twig', ['recipes' => $recipes]);
    }
}
