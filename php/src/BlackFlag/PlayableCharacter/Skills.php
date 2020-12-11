<?php
declare(strict_types=1);

namespace BlackFlag\PlayableCharacter;

use BlackFlag\PlayableCharacter\Event\CharacterCreated;
use BlackFlag\PlayableCharacter\Exception\SkillNotLearned;
use BlackFlag\Skill;
use BlackFlag\Skill\Domain;
use EventSourcing\ChildEntity;

class Skills extends ChildEntity
{
    /** @var array<string, Skill>  */
    private array $skills;

    public function __construct(Character $character)
    {
        parent::__construct($character);
    }

    public function get(string $name): Skill
    {
        if (!isset($this->skills[$name])) {
            throw new SkillNotLearned(sprintf('Skill "%s" not learned', $name));
        }

        return $this->skills[$name];
    }

    protected function applyCharacterCreated(CharacterCreated $event): void
    {
        $skills = $event->getSkills();

        foreach ($skills as $data) {
            $index = isset($data['special']) ? $data['special'] : $data['domain'];

            $this->skills[$index] = new Skill(
                new Domain($data['domain'], isset($data['special']) ? $data['special'] : null),
                $data['level'],
                isset($data['pro']) ? $data['pro'] : false,
            );
        }
    }
}