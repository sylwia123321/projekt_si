<?php
/**
 * Avatar controller.
 */

namespace App\Controller;

use App\Entity\Avatar;
use App\Entity\User;
use App\Form\Type\AvatarType;
use App\Service\AvatarServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AvatarController.
 */
#[Route('/avatar')]
class AvatarController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param AvatarServiceInterface $avatarService
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManagerInterface $entityManager, private readonly AvatarServiceInterface $avatarService, private readonly TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Response
     */
    #[Route(name: 'avatar_index', methods: 'GET')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getAvatar()) {
            return $this->redirectToRoute(
                'avatar_edit',
                ['id' => $user->getId()]
            );
        }

        return $this->redirectToRoute('avatar_create');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route(
        '/create',
        name: 'avatar_create',
        methods: 'GET|POST'
    )]
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getAvatar()) {
            return $this->redirectToRoute(
                'avatar_edit',
                ['id' => $user->getId()]
            );
        }

        $avatar = new Avatar();
        $form = $this->createForm(
            AvatarType::class,
            $avatar,
            ['action' => $this->generateUrl('avatar_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $this->avatarService->create(
                $file,
                $avatar,
                $user
            );

            $this->entityManager->persist($avatar);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'avatar/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     * @param Avatar $avatar
     * @return Response
     */
    #[Route(
        '/{id}/edit',
        name: 'avatar_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    public function edit(Request $request, Avatar $avatar): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getAvatar()) {
            return $this->redirectToRoute('avatar_create');
        }

        $form = $this->createForm(
            AvatarType::class,
            $avatar,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('avatar_edit', ['id' => $avatar->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $this->avatarService->update(
                $file,
                $avatar,
                $user
            );

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'avatar/edit.html.twig',
            [
                'form' => $form->createView(),
                'avatar' => $avatar,
            ]
        );
    }
}
