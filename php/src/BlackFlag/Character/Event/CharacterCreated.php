<?php
declare(strict_types=1);

namespace BlackFlag\Character\Event;

use EventSourcing\Event;

class CharacterCreated implements Event
{
    private string $firstname;

    private string $lastname;

    private string $nickname;

    private int $age;

    private bool $gender;

    /**
     * @var array<string, int>
     */
    private array $attributes;

    /**
     * @param array<string, int> $attributes
     */
    public function __construct(
        string $firstname,
        string $lastname,
        string $nickname,
        int $age,
        bool $gender,
        array $attributes
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->nickname = $nickname;
        $this->age = $age;
        $this->gender = $gender;
        $this->attributes = $attributes;
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
        );
    }

    /**
     * @return array<string, mixed>
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
}