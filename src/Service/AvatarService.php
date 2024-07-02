<?php
/**
 * Avatar service.
 */

namespace App\Service;

use App\Entity\Avatar;
use App\Entity\User;
use App\Repository\AvatarRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class AvatarService.
 */
class AvatarService implements AvatarServiceInterface
{
    public function __construct(private readonly string $targetDirectory, private readonly AvatarRepository $avatarRepository, private readonly FileUploadServiceInterface $fileUploadService, private readonly Filesystem $filesystem)
    {
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(UploadedFile $uploadedFile, Avatar $avatar, User $user): void
    {
        $filename = $avatar->getFilename();

        if (null !== $filename) {
            $this->filesystem->remove(
                $this->targetDirectory.'/'.$filename
            );

            $this->create($uploadedFile, $avatar, $user);
        }
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(UploadedFile $uploadedFile, Avatar $avatar, User $user): void
    {
        $avatarFilename = $this->fileUploadService->upload($uploadedFile);

        $avatar->setUser($user);
        $avatar->setFilename($avatarFilename);
        $this->avatarRepository->save($avatar);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Avatar $avatar): void
    {
        $filename = $avatar->getFilename();

        if (null !== $filename) {
            $this->filesystem->remove($this->targetDirectory.'/'.$filename);
            $avatar->setFilename(null);
            $this->avatarRepository->save($avatar);
        }
    }
}
