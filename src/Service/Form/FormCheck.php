<?php

namespace App\Service\Form;

use App\Model\UserManager;

class FormCheck
{
    protected array $errors = [];

    public function cleanPost(array $post): array
    {
        $post = array_map('trim', $post);
        return $post;
    }

    public function emptyField(string $fieldName, string $field): void
    {
        if (empty($field)) {
            $this->errors[$fieldName] = 'Veuillez renseigner le champ : ' . $fieldName;
        }
    }

    public function isPseudoUsed(string $pseudo): void
    {

        $userManager = new UserManager();
        $users = $userManager->selectAll();
        foreach ($users as $user) {
            if ($pseudo === $user['pseudo']) {
                $this->errors['pseudoAlreadyUsed'] = 'Pseudo déjà utilisé';
            }
        }
    }

    public function isLengthRespected(int $length, string $postField, string $fieldName): void
    {
        if (strlen($postField) > $length) {
            $this->errors[$fieldName . 'Length'] = 'Longueur maximale du champ : ' . $fieldName
                                        . ' est de ' . $length . ' caractères';
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors): FormCheck
    {
        $this->errors = $errors;
        return $this;
    }
}
