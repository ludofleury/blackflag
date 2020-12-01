<?php
declare(strict_types=1);

namespace BlackFlag\Skill;

interface Knowledge
{
    public const BALLISTICS = 'ballistics';
    public const CARTOGRAPHY = 'cartography';
    public const TRADE = 'trade';
    public const EXPERTISE = 'specialized knowledge';
    public const EXPERTISE_LAW = 'law';
    public const EXPERTISE_GEOGRAPHY = 'geography';
    public const EXPERTISE_HISTORY = 'history';
    public const HERBALISM = 'herbalism';
    public const NAVAL_ENGINEERING = 'naval engineering';
    public const STEWARDSHIP = 'stewardship';
    public const READ_WRITE = 'read/write';
    public const MEDICINE = 'medicine';
    public const RELIGION = 'religion';
    public const SCIENCES = 'sciences';
    public const TACTIC = 'tactic';
}