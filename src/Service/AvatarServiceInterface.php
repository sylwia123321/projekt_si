<?php
/**
 * Avatar service interface.
 */

namespace App\Service;

use App\Entity\Avatar;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\User;

/**
 * Class Avatar service.
 */
interface AvatarServiceInterface
{
    /**
     * Update avatar.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Avatar       $avatar       Avatar entity
     * @param User         $user         User interface
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

    /**
     * Delete avatar.
     *
     * @param Avatar $avatar Avatar entity to delete
     */
    public function delete(Avatar $avatar): void;
}
