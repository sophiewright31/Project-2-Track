<?php

namespace App\Controller;

use App\Model\AbstractManager;
use App\Model\SongManager;
use App\Model\BadgeManager;
use App\Model\StyleManager;
use App\Model\UserBadgeManager;
use App\Model\UserManager;
use App\Service\Badge\BadgeValidator;
use App\Service\Form\BadgeFormCheck;

class AdminController extends AbstractController
{
    private const MAX_LENGTH_NAME_BADGE = 100;
    private const MAX_LENGTH_TEXT_BADGE = 255;
    private const MAX_LENGTH_URL_BADGE = 255;

    public function index(): string
    {
        if (isset($_SESSION["role"])) {
            if ($_SESSION["role"] === 'admin') {
                    $userManager = new UserManager();
                    $users = $userManager->showNbUser();
                    $monthlyUsers = $userManager->showNbUserByMonth();
                    $songManager = new SongManager();
                    $videos = $songManager->selectAllSong();
                    $songs = $songManager->showNbSong();
                    $monthlySongs = $songManager->showNbSongsByMonth();
                    $dailySongs = $songManager->showNbSongsByDay();
                    $badgeManager = new BadgeManager();
                    $badges = $badgeManager->showNbBadge();
                    return $this->twig->render('admin/stat.html.twig', [
                        'nbUsers' => $users,
                        'monthlyUsers' => $monthlyUsers,
                        'nbSongs' => $songs,
                        'monthlySongs' => $monthlySongs,
                        'dailySongs' => $dailySongs,
                        'badges' => $badges,
                        'videos' => $videos,
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

    public function deleteSong(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminManager = new SongManager();
            $adminManager->delete($id);
            header('Location:/admin/index');
        }
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
            $badgeValidator = new BadgeValidator();
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
            $badgeManager = new BadgeManager();
            $userManager = new UserManager();
            $users = $userManager->selectAll();


            $badgeCheck = new BadgeFormCheck();
            $_POST = $badgeCheck->cleanPost($_POST);
            $badgeCheck->emptyField('name', $_POST['name']);
            $badgeCheck->emptyField('picture_url', $_POST['picture_url']);
            $badgeCheck->emptyField('description', $_POST['description']);
            $badgeCheck->isLengthRespected(self::MAX_LENGTH_NAME_BADGE, $_POST['name'], 'name');
            $badgeCheck->isLengthRespected(self::MAX_LENGTH_TEXT_BADGE, $_POST['description'], 'description');
            $badgeCheck->isLengthRespected(self::MAX_LENGTH_URL_BADGE, $_POST['picture_url'], 'picture_url');
            $errors = $badgeCheck->getErrors();
            if (empty($errors)) {
                $badgeManager->insert($_POST);
                $badges = $badgeManager->selectAll();
                return $this->twig->render('admin/badges.html.twig', [
                    'badges' => $badges,
                    'users' => $users,
                    'success_badge_create' => true,
                ]);
            } else {
                $badges = $badgeManager->selectAll();
                return $this->twig->render('admin/badges.html.twig', [
                    'badges' => $badges,
                    'users'  => $users,
                    'errors' => $errors,
                ]);
            }
        }
        //If don't come from a post go to error 405
        header("HTTP/1.0 405 Method Not Allowed");
        return (new ErrorHandleController())->badMethod();
    }
}
