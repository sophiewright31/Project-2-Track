<?php

namespace App\Service\Badge;

class BadgeValidator
{
    private array $errors = [];
    private array $post;

    public function __construct($post)
    {
        $this->post = $post;
    }
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

    public function badgeAlreadyGiven(array $userBadges, array $post): array
    {
        foreach ($userBadges as $userBadge) {
            if ($userBadge['user_id'] === $post['user_id'] && $userBadge['badge_id'] === $post['badge_id']) {
                $this->errors['badgeAlreadyGiven'] = 'Cet utilisateur possède déjà le badge sélectionné';
            }
        }
        return $this->errors;
    }



    public function setErrors(array $errors): BadgeValidator
    {
        $this->errors = $errors;
        return $this;
    }

    public function getPost(): array
    {
        return $this->post;
    }

    public function setPost(array $post): BadgeValidator
    {
        $this->post = $post;
        return $this;
    }
}
