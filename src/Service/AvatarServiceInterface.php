<?php
/**
 * Avatar service interface.
 */

namespace App\Service;

use App\Entity\Avatar;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
use App\Repository\AvatarRepository;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Avatar service.
 */
interface AvatarServiceInterface
{
    /**
     * Update avatar.
     *
     * @param UploadedFile  $uploadedFile Uploaded file
     * @param Avatar        $avatar       Avatar entity
     * @param UserInterface $user         User interface
     */
    public function update(UploadedFile $uploadedFile, Avatar $avatar, User $user): void;

    /**
     * Create avatar.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Avatar       $avatar       Avatar entity
     * @param User         $user         User entity
     */
    public function create(UploadedFile $uploadedFile, Avatar $avatar, User $user): void;
}
