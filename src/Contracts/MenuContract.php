<?php

namespace KY\AdminPanel\Contracts;

interface MenuContract
{
    public function addItem();
    public function handle();
    public function display($type = null, array $options = []);
    public function getSlug():string;
    public function getName():string;
}
