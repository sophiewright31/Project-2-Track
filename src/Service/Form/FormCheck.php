<?php

namespace App\Service\Form;

use App\Model\UserManager;

class FormCheck
{

    public function cleanPost(array $post): array
    {
        $post = array_map('trim', $post);
        return $post;
    }

    public function emptyField(string $fieldName, string $field): string
    {
        return empty($field) ? 'Veuillez renseigner le champ : ' . $fieldName : '';
    }

    public function cleanError(array $errors): array
    {
        $errorsCleaned = [];
        foreach ($errors as $field => $error) {
            if ($error !== '' && $error !== false) {
                $errorsCleaned[$field] = $error;
            }
        }
        return $errorsCleaned;
    }

    public function isPseudoUsed(string $pseudo): bool
    {
        $result = false;
        $userManager = new UserManager();
        $users = $userManager->selectAll();
        foreach ($users as $user) {
            if ($pseudo === $user['pseudo']) {
                $result = true;
            }
        }
        return $result;
    }

    public function isLengthRespected(int $length, string $postField): bool
    {
        return strlen($postField) > $length;
    }
}
