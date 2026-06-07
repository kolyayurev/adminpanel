<?php

namespace KY\AdminPanel\Blocks;

class Col extends BaseBlock
{
    protected ?int $xs = null;

    protected ?int $sm = null;

    protected ?int $md = null;

    protected ?int $lg = null;

    public function getXs(): ?int
    {
        return $this->xs;
    }

    /**
     * @param  int  $common
     */
    public function xs(int $xs): Col
    {
        $this->xs = $this->validate($xs);

        return $this;
    }

    public function getSm(): ?int
    {
        return $this->sm;
    }

    public function sm(int $sm): Col
    {
        $this->sm = $this->validate($sm);

        return $this;
    }

    public function getMd(): ?int
    {
        return $this->md;
    }

    public function md(int $md): Col
    {
        $this->md = $this->validate($md);

        return $this;
    }

    public function getLg(): ?int
    {
        return $this->lg;
    }

    public function lg(int $lg): Col
    {
        $this->lg = $this->validate($lg);

        return $this;
    }

    public function getColumns(): string
    {
        return
            'col'.($this->getXs() ? '-'.$this->getXs() : '-12')
            .($this->getSm() ? ' col-sm-'.$this->getSm() : '')
            .($this->getMd() ? ' col-md-'.$this->getMd() : '')
            .($this->getLg() ? ' col-lg-'.$this->getLg() : '');
    }

    private function validate(int $col): int
    {
        return ($col >= 1 && $col <= 12) ? $col : 12;
    }
}
