<?php
declare(strict_types=1);

namespace KY\AdminPanel\Traits;

use Illuminate\Support\Collection;
use KY\AdminPanel\Blocks\Card;
use KY\AdminPanel\Blocks\Col;
use KY\AdminPanel\Blocks\Row;

trait HasLayout
{
    /**
     * @return Collection
     */
    public function layout(): Collection
    {
        return collect([
            Row::blocks(
                Col::blocks(
                    Card::blocks('*')
                )
            )
        ]);
    }


    /**
     * @return Collection
     */
    public function getLayout() : Collection
    {
        return $this->layout();
    }

}
