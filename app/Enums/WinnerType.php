<?php

namespace App\Enums;

enum WinnerType: int
{
    case Draw = -1;
    case FirstUser = 0;
    case SecondUser = 1;
}
