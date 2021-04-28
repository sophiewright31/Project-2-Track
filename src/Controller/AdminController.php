<?php

namespace App\Controller;

use App\Model\SongManager;
use App\Model\BadgeManager;
use App\Model\UserBadgeManager;
use App\Model\UserManager;
use App\Service\Badge\BadgeValidator;
use App\Controller\ErrorHandleController;

class AdminController extends AbstractController
{
    public function index(): string
    {
        if (isset($_SESSION["role"])) {
            if ($_SESSION["role"] === 'admin') {
                return $this->twig->render('admin/admin.html.twig');
            } else {
                header("HTTP/1.0 403 Forbidden");
                return (new ErrorHandleController())->forbidden();
            }
        } else {
            header("HTTP/1.0 403 Forbidden");
            return (new ErrorHandleController())->forbidden();
        }
    }

    public function showAllBadges(): string
    {
        if (isset($_SESSION["role"])) {
            if ($_SESSION["role"] === 'admin') {
                $badgeManager = new BadgeManager();
                $userManager = new UserManager();
                $users = $userManager->selectAll();
                $badges = $badgeManager->selectAll();
                return $this->twig->render('admin/badges.html.twig', [
                    'badges' => $badges,
                    'users' => $users,
                ]);
            } else {
                    header("HTTP/1.0 403 Forbidden");
                    return (new ErrorHandleController())->forbidden();
            }
        } else {
                header("HTTP/1.0 403 Forbidden");
                return (new ErrorHandleController())->forbidden();
        }
    }
//TODO changer de nom du delete (supprimer une chanson actuellement)

    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminManager = new SongManager();
            $adminManager->delete($id);
            header('Location: /');
        }
        //If don't come from a post go to error 405
        header("HTTP/1.0 405 Method Not Allowed");
        return (new ErrorHandleController())->badMethod();
    }

    public function deleteBadge(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $badgeManager = new BadgeManager();
            $badgeManager->delete($id);
            header('Location: /admin/showAllBadges');
        }
    }

    public function attributeBadgeToUser(): string
    {
        // For twig
        $badgeManager = new BadgeManager();
        $userManager = new UserManager();
        $users = $userManager->selectAll();
        $badges = $badgeManager->selectAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check of Badge // User
            $userBadgeManager = new UserBadgeManager();
            $userBadges = $userBadgeManager->selectAll();
            $badgeValidator = new BadgeValidator($_POST);
            $badgeValidator->incorrectIDField('user_id', $_POST['user_id']);
            $badgeValidator->incorrectIDField('badge_id', $_POST['badge_id']);
            $badgeValidator->badgeAlreadyGiven($userBadges, $_POST);
            $errors = $badgeValidator->getErrors();
            if (!empty($errors)) {
                return $this->twig->render('admin/badges.html.twig', [
                    'badges' => $badges,
                    'users' => $users,
                    'errors' => $errors,
                ]);
            }
            $userBadgeManager->insert($_POST);
            return $this->twig->render('admin/badges.html.twig', [
                'badges' => $badges,
                'users' => $users,
                'success_badge_attribute' => true,
            ]);
        }

        //If don't come from a post go to error 405
        header("HTTP/1.0 405 Method Not Allowed");
        return (new ErrorHandleController())->badMethod();
    }
    public function addBadge()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['name'])) {
                $errors['name'] = 'Please enter your badge name';
            }
            if (empty($_POST['picture_url'])) {
                $errors['picture_url'] = 'Please enter your picture URL';
            }
            if (empty($_POST['description'])) {
                $errors['description'] = 'Please enter your badge description';
            }

            if (empty($errors)) {
                $badgeManager = new BadgeManager();
                $badgeManager->insert($_POST);
                header('Location: /admin/showAllBadges');
            }
        }
        //If don't come from a post go to error 405
        header("HTTP/1.0 405 Method Not Allowed");
        return (new ErrorHandleController())->badMethod();
    }
}
