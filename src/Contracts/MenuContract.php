<?php

namespace KY\AdminPanel\Contracts;

interface MenuContract
{
    public function items();
    public function getSlug():string;
    public function getName():string;
}
