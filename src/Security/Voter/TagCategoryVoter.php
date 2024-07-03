<?php
/**
 * TagCategory voter.
 */

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TagCategoryVoter.
 */
class TagCategoryVoter extends Voter
{
    public const MANAGE = 'manage';

    /**
     * Determines if the voter supports the given attribute.
     *
     * @param string $attribute The attribute to check
     * @param mixed  $subject   The subject to vote on
     *
     * @return bool True if this voter supports the attribute, false otherwise
     */
    protected function supports(string $attribute, $subject): bool
    {
        return self::MANAGE === $attribute;
    }

    /**
     * Votes on whether the given attribute should be granted for the given subject.
     *
     * @param string         $attribute The attribute to check
     * @param mixed          $subject   The subject to vote on
     * @param TokenInterface $token     The token containing the user information
     *
     * @return bool True if the attribute is granted, false otherwise
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        return (bool) 'ROLE_ADMIN';
    }
}
