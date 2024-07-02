<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * Class UserController.
 */
#[Route('/user')]
class UserController extends AbstractController
{
    private UserServiceInterface $userService;
    private TranslatorInterface $translator;

    /**
     * @param UserServiceInterface $userService
     * @param TranslatorInterface  $translator
     */
    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $page = $request->query->getInt('page', 1);
            $pagination = $this->userService->getPaginatedList($page);

            $result = $this->render('user/index.html.twig', [
                'pagination' => $pagination,
            ]);
        }
        else {
            $this->addFlash(
                'danger',
                $this->translator->trans('message.access_denied')
            );
            $result = $this->redirectToRoute('recipe_index');
        }
        return $result;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/create', name: 'user_create', methods: ['GET|POST'])]
    public function create(Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $user = new User();
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->userService->save($user);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.created_successfully')
                );

                return $this->redirectToRoute('user_index');
            }

            return $this->render('user/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        else {
            $this->addFlash(
                'danger',
                $this->translator->trans('message.access_denied')
            );
            $result = $this->redirectToRoute('recipe_index');
        }
        return $result;
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return Response
     */
    #[Route('/{id}/edit', name: 'user_edit', requirements: ['id' => '\d+'], methods: ['GET|PUT'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(
            UserType::class,
            $user,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('user_edit', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @param User $user
     *
     * @return Response
     */
    #[Route('/{id}', name: 'user_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(User $user): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $result = $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
        }
        else {
            $this->addFlash(
                'danger',
                $this->translator->trans('message.access_denied')
            );
            $result = $this->redirectToRoute('recipe_index');
        }
        return $result;
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return Response
     */
    #[Route('/{id}/delete', name: 'user_delete', requirements: ['id' => '\d+'], methods: ['DELETE|GET'])]
    public function delete(Request $request, User $user): Response
    {
        $form = $this->createForm(FormType::class, $user, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('user_delete', ['id' => $user->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userService->deleteUserWithRelatedEntities($user);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.deleted_successfully')
                );
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash(
                    'error',
                    ''
                );
            }

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/delete.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
