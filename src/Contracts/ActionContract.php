<?php

namespace KY\AdminPanel\Contracts;

interface ActionContract
{
    public function getTag(): string;

    public function getIcon(): string;

    public function getTitle(): string;

    public function getColor(): string;

    public function getRoute(): string;

    public function getPolicyName(): string;

    public function getTemplate(): string;

    public function getAttributes(): array;

    public function convertAttributesToHtml(): string;
}
