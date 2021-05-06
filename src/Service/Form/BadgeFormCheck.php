<?php

namespace App\Service\Form;

class BadgeFormCheck extends FormCheck
{
    public function cleanPost(array $post): array
    {
        $url = $post['picture_url'];

        $url = filter_var($url, FILTER_SANITIZE_URL);
        if (!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            $this->errors['urlNotValid'] = "L'URL indiqué : $url is not a valid URL";
        }
        $post['picture_url'] = $url;
        return parent::cleanPost($post);
    }
}
