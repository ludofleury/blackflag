<?php
declare(strict_types=1);

namespace BlackFlag\Skill\Domain;

interface Technical
{
    public const ART = 'art';
    public const ARTILLERY_GUN_LAYING = 'gun laying';
    public const ARTILLERY_GUN_RELOADING = 'gun reloading';
    public const CRAFTING = 'crafting';
    public const CRAFTING_CAULK = 'caulk';
    public const CRAFTING_CARPENTRY = 'carpentry';
    public const CRAFTING_SAILMAKER = 'sailmaker';
    public const CRAFTING_COOKING = 'cooking';
    public const HUNTING = 'hunting';
    public const SURGERY = 'surgery';
    public const ANIMAL_TRAINING = 'animal training';
    public const FIRST_AID = 'first aid';
}