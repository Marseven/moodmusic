<?php

namespace Common\Database;

use Illuminate\Pagination\CursorPaginator;

class AppCursorPaginator extends CursorPaginator
{
    public function toArray()
    {
        $data = parent::toArray();
        $next = $this->nextCursor();
        $prev = $this->previousCursor();
        $data['next_cursor'] = $next ? $next->encode() : null;
        $data['prev_cursor'] = $prev ? $prev->encode() : null;
        return $data;
    }
}
