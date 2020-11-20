<?php

namespace BlackFlag\Character\Event;

use EventSourcing\Event;

class CharacterCreated implements Event
{
    private string $firstname;

    private string $lastname;

    private string $nickname;

    private int $age;

    private bool $gender;

    private array $attributes;

    // private string $background;
    // private array $skills;/**

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

    public function getAttributes(): array
    {
        return $this->attributes;
    }

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

}