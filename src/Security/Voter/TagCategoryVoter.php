<?php
// src/Security/Voter/TagCategoryVoter.php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TagCategoryVoter extends Voter
{
    // Stała reprezentująca uprawnienie do zarządzania
    const MANAGE = 'manage';

    // Metoda sprawdzająca, czy Voter obsługuje daną kombinację atrybutu i obiektu
    protected function supports(string $attribute, $subject): bool
    {
        // Obsługujemy tylko jedno uprawnienie 'manage'
        return $attribute === self::MANAGE;
    }

    // Metoda decydująca, czy użytkownik ma dostęp do danego atrybutu i obiektu
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // Pobranie użytkownika z tokenu
        $user = $token->getUser();

        // Jeśli użytkownik nie jest zalogowany, nie ma dostępu
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Admin ma zawsze dostęp
        if ('ROLE_ADMIN') {
            return true;
        }

        // W przeciwnym razie nie ma dostępu
        return false;
    }
}
