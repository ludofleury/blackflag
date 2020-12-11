<?php
declare(strict_types=1);

namespace BlackFlag\PlayableCharacter\Event;

use EventSourcing\Event;

class CharacterCreated implements Event
{
    /**
     * @param array<string> $occupations
     * @param array{
     *     adaptability: int,
     *     charisma: int,
     *     constitution: int,
     *     dexterity: int,
     *     expression: int,
     *     knowledge: int,
     *     perception: int,
     *     power: int,
     *     strength: int
     *  } $characteristics
     * @param array<array{name: string, level: int, special?: string, pro?: bool}>  $skills
     */
    public function __construct(
        private string $firstname,
        private string $lastname,
        private string $nickname,
        private int $age,
        private bool $gender,
        private array $occupations,
        private array $characteristics,
        private array $skills,
    ) {
    }

    static public function fromArray(array $data): self
    {
        return new self(
            $data['firstname'],
            $data['lastname'],
            $data['nickname'],
            $data['age'],
            $data['gender'],
            $data['occupations'],
            $data['characteristics'],
            $data['skills'],
        );
    }

    /**
     * @return array{
     *  firstname: string,
     *  lastname: string,
     *  nickname: string,
     *  age: int,
     *  gender: bool,
     *  occupation: string,
     *  characteristics: array{
     *     adaptability: int,
     *     charisma: int,
     *     constitution: int,
     *     dexterity: int,
     *     expression: int,
     *     knowledge: int,
     *     perception: int,
     *     power: int,
     *     strength: int
     *  },
     *  skills: array<array{domain: string, level: int, special?: string, pro?: bool}>,
     * }
     */
    public function toArray(): array
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'nickname' => $this->nickname,
            'age' => $this->age,
            'gender' => $this->gender,
            'occupations' => $this->occupations,
            'characteristics' => $this->characteristics,
            'skills' => $this->skills,
        ];
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getGender(): bool
    {
        return $this->gender;
    }

    /**
     * @return array{
     *  adaptability: int,
     *  charisma: int,
     *  constitution: int,
     *  dexterity: int,
     *  expression: int,
     *  knowledge: int,
     *  perception: int,
     *  power: int,
     *  strength: int
     * }
     */
    public function getCharacteristics(): array
    {
        return $this->characteristics;
    }

    /**
     * @return array<array{domain: string, level: int, special?: string, pro?: bool}>
     */
    public function getSkills(): array
    {
        return $this->skills;
    }
}