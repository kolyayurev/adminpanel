<?php

namespace KY\AdminPanel\Repositories;

/**
 * Репозиторий-заглушка для DataType, которому не нужна собственная логика запроса —
 * достаточно класса модели. Собирается автоматически в BaseDataType::getRepository()
 * из объявленного $modelClass, если DataType не назначил свой репозиторий явно.
 */
class ModelRepository extends BaseRepository
{
    public function __construct(protected string $modelClass) {}

    public function modelClass(): string
    {
        return $this->modelClass;
    }
}
