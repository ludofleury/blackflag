<?php
declare(strict_types=1);

namespace BlackFlag\PlayableCharacter;

use Rpg\AbstractStatistic;
use Rpg\Statistic;

final class Skill extends AbstractStatistic implements Statistic
{
    private bool $category;

    private bool $isProfessional;

    private bool $isCommand;

    private int $experience;

    private bool $isImprovedByFieldExperience;

    public function __construct(
        bool $category,
        string $name,
        int $value,
        bool $isProfessional,
        bool $isCommand,
        int $experience,
        bool $isImprovedByFieldExperience
    ) {
        $this->category = $category;
        $this->isProfessional = $isProfessional;
        $this->isCommand = $isCommand;
        $this->experience = $experience;
        $this->isImprovedByFieldExperience = $isImprovedByFieldExperience;
    }


}