<?php

namespace App;

enum TodoStatusEnum: string
{
    case Pending = 'pending';
    case Open = 'open';
    case In_progress = 'in_progress';
    case Completed = 'completed';
}
