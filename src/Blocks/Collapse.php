<?php

namespace KY\AdminPanel\Blocks;

use Illuminate\Support\Str;

class Collapse extends Card
{
    protected string $id;

    public function getId(): string
    {
        if (empty($this->id)) {
            $this->id = Str::lower($this->hasHeader()?Str::slug($this->getHeader()):Str::random(10));
        }

        return $this->id;
    }

}
