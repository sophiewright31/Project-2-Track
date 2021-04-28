<?php

namespace App\Service\Badge;

use App\Model\UserBadgeManager;

class GamificationCalculator
{

    public function badgePower($contributionPower, $userId): string
    {
        $badgeName = '';
        switch ($contributionPower) {
            case 5:
                $userBadgeManager = new UserBadgeManager();
                $badgeName = '5 power given';
                $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                break;
            case 100:
                $userBadgeManager = new UserBadgeManager();
                $badgeName = '100 power given';
                $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                break;
            case 10000:
                $userBadgeManager = new UserBadgeManager();
                $badgeName = '10000 power given';
                $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                break;
            case 100000:
                $userBadgeManager = new UserBadgeManager();
                $badgeName = '100000 power given';
                $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                break;
            case 1000000:
                $userBadgeManager = new UserBadgeManager();
                $badgeName = '1000000 power given';
                $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                break;
        }
        return $badgeName;
    }

    public function badgeSongs($songsPosted, $userId): string
    {
        $badgeName = '';
        switch ($songsPosted) {
            case 1:
                $userBadgeManager = new UserBadgeManager();
                $badgeName = '1 music shared';
                $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                break;
            case 5:
                $userBadgeManager = new UserBadgeManager();
                $badgeName = '5 musics shared';
                $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                break;
            case 10:
                $userBadgeManager = new UserBadgeManager();
                $badgeName = '10 musics shared';
                $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                break;
            case 50:
                $userBadgeManager = new UserBadgeManager();
                $badgeName = '50 musics shared';
                $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                break;
            case 100:
                $userBadgeManager = new UserBadgeManager();
                $badgeName = '100 musics shared';
                $userBadgeManager->attributeByBadgeName($badgeName, $userId);
                break;
        }
        return $badgeName;
    }
}
