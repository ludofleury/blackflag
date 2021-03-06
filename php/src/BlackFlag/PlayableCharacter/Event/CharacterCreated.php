<?php
declare(strict_types=1);

namespace BlackFlag\PlayableCharacter\Event;

use EventSourcing\Event;

class CharacterCreated implements Event
{
    /**
     * @param array<string, int> $attributes
     * @param array<array{name: string, level: int, special?: string, pro?: bool}>  $skills
     */
    public function __construct(
        private string $firstname,
        private string $lastname,
        private string $nickname,
        private int $age,
        private bool $gender,
        private array $attributes,
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
            $data['attributes'],
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
     *  attributes: array<string, int>,
     *  skills: array<array{name: string, level: int, special?: string, pro?: bool}>,
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
            'attributes' => $this->attributes,
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
     * @return array<string, int>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return array<array{name: string, level: int, special?: string, pro?: bool}>
     */
    public function getSkills(): array
    {
        return $this->skills;
    }
}