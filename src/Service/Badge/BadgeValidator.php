<?php

namespace App\Service\Badge;

use App\Model\BadgeManager;
use App\Model\UserBadgeManager;

class BadgeValidator
{
    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function incorrectIDField(string $field, $id): array
    {
        if (!is_numeric($id)) {
            $this->errors[$field] = 'Le champ ' . $field . ' est incorrect, avez-vous modifié le 
                                          fichier HTML dans votre inspecteur ?';
        }
        return $this->errors;
    }

    public function badgeAlreadyGiven(array $badgesAttributed, array $badgeSelected): array
    {
        foreach ($badgesAttributed as $badgeAttributed) {
            if (
                $badgeAttributed['user_id'] === $badgeSelected['user_id'] &&
                $badgeAttributed['badge_id'] === $badgeSelected['badge_id']
            ) {
                $this->errors['badgeAlreadyGiven'] = 'Cet utilisateur possède déjà le badge sélectionné';
            }
        }
        return $this->errors;
    }


    public function formatBadgeForTest(int $userId, string $badgeName): array
    {
        $badgeTested = [];
        $badgeManager = new BadgeManager();
        $badgeId = $badgeManager->idByName($badgeName);
        $badgeTested['user_id'] = strval($userId);
        $badgeTested['badge_id'] = $badgeId['id'];
        return $badgeTested;
    }


    public function checkDuplicateByName(int $userid, string $badgeName): bool
    {
        $errors = [];
        $userBadgeManager = new UserBadgeManager();
        $userBadges = $userBadgeManager->selectAll();
        $badgeTested = $this->formatBadgeForTest($userid, $badgeName);
        $errors = $this->badgeAlreadyGiven($userBadges, $badgeTested);
        return !empty($errors);
    }



    public function setErrors(array $errors): BadgeValidator
    {
        $this->errors = $errors;
        return $this;
    }
}
