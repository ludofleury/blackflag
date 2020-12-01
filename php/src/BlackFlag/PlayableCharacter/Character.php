<?php
declare(strict_types=1);

namespace BlackFlag\PlayableCharacter;

use BlackFlag\PlayableCharacter\Event\CharacterCreated;
use BlackFlag\Resolution\SkillName;
use BlackFlag\Skill;
use EventSourcing\AggregateRoot;
use Ramsey\Uuid\Uuid;

final class Character extends AggregateRoot
{
    private string $firstname;

    private string $lastname;

    private string $nickname;

    private int $age;

    private bool $gender;

    private PrimaryAttributes $primaries;

    private Skills $skills;

//    private SecondaryAttributes $secondaries;

    /**
     * @param array<string, int> $attributes
     * @param array<array{name: string, level: int, special?: string, pro?: bool}>  $skills
     */
    public static function create(
        string $firstname,
        string $lastname,
        string $nickname,
        int $age,
        bool $gender,
        array $attributes,
        array $skills,
    ): Character {
        $character = new self(Uuid::uuid4());

        $event = new CharacterCreated(
            $firstname,
            $lastname,
            $nickname,
            $age,
            $gender,
            $attributes,
            $skills,
        );
        $character->apply($event);

        return $character;
    }

    public function getSkill(string $name): Skill
    {
        return $this->skills->get($name);
    }

    protected function applyCharacterCreated(CharacterCreated $event): void
    {
        $this->firstname = $event->getFirstname();
        $this->lastname = $event->getLastname();
        $this->nickname = $event->getNickname();
        $this->age = $event->getAge();
        $this->gender = $event->getGender();
        $this->primaries = new PrimaryAttributes($this);
        $this->skills = new Skills($this);
    }
}