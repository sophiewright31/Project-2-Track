<?php

namespace App\Service\Badge;

use DateTime;
use App\Model\UserBadgeManager;

class GamificationCalculator
{


    public function badgePower($contributionPower, $userId): string
    {
        $badgeName = '';
        $userBadgeManager = new UserBadgeManager();
        $badgeCheck = new BadgeValidator();
        switch ($contributionPower) {
            case 5:
                $badgeName = '5 power given';
                if (!$badgeCheck->checkDuplicateByName($userId, $badgeName)) {
                    $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                } else {
                    $badgeName = '';
                }
                break;
            case 100:
                $badgeName = '100 power given';
                if (!$badgeCheck->checkDuplicateByName($userId, $badgeName)) {
                    $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                } else {
                    $badgeName = '';
                }
                break;
            case 10000:
                $badgeName = '10000 power given';
                if (!$badgeCheck->checkDuplicateByName($userId, $badgeName)) {
                    $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                } else {
                    $badgeName = '';
                }
                break;
            case 100000:
                $badgeName = '100000 power given';
                if (!$badgeCheck->checkDuplicateByName($userId, $badgeName)) {
                    $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                } else {
                    $badgeName = '';
                }
                break;
            case 1000000:
                $badgeName = '1000000 power given';
                if (!$badgeCheck->checkDuplicateByName($userId, $badgeName)) {
                    $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                } else {
                    $badgeName = '';
                }
                break;
        }
        return $badgeName;
    }

    public function badgeSongs($songsPosted, $userId): string
    {
        $badgeName = '';
        $badgeCheck = new BadgeValidator();
        $userBadgeManager = new UserBadgeManager();
        switch ($songsPosted) {
            case 1:
                $badgeName = '1 music shared';
                if (!$badgeCheck->checkDuplicateByName($userId, $badgeName)) {
                    $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                } else {
                    $badgeName = '';
                }
                break;
            case 5:
                $badgeName = '5 musics shared';
                if (!$badgeCheck->checkDuplicateByName($userId, $badgeName)) {
                    $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                } else {
                    $badgeName = '';
                }
                break;
            case 10:
                $badgeName = '10 musics shared';
                if (!$badgeCheck->checkDuplicateByName($userId, $badgeName)) {
                    $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                } else {
                    $badgeName = '';
                }
                break;
            case 50:
                $badgeName = '50 musics shared';
                if (!$badgeCheck->checkDuplicateByName($userId, $badgeName)) {
                    $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                } else {
                    $badgeName = '';
                }
                break;
            case 100:
                $badgeName = '100 musics shared';
                if (!$badgeCheck->checkDuplicateByName($userId, $badgeName)) {
                    $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                } else {
                    $badgeName = '';
                }
                break;
        }
        return $badgeName;
    }

    public function powerBadgeByNight(int $userId): string
    {
        $badgeName = '';
        $date = new DateTime();
        $hour = intval($date->format("H"));

        if ($hour >= 2 && $hour <= 5) {
            $badgeName = 'Sleepy Vote';
            $badgeCheck = new BadgeValidator();
            $userBadgeManager = new UserBadgeManager();
            if (!$badgeCheck->checkDuplicateByName($userId, $badgeName)) {
                $userBadgeManager->attributeByBadgeName($badgeName, $userId);
            } else {
                $badgeName = '';
            }
        }
        return $badgeName;
    }

    public function powerBadgeWeekEnd(int $userId): string
    {
        $badgeName = '';
        $date = new DateTime();
        $day = $date->format("D");

        if ($day === 'Sun' || $day === 'Sat') {
            $badgeName = 'Weekend vote';
            $badgeCheck = new BadgeValidator();
            $userBadgeManager = new UserBadgeManager();
            if (!$badgeCheck->checkDuplicateByName($userId, $badgeName)) {
                $userBadgeManager->attributeByBadgeName($badgeName, $userId);
            } else {
                $badgeName = '';
            }
        }
        return $badgeName;
    }
}
