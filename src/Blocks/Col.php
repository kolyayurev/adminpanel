<?php
namespace KY\AdminPanel\Blocks;

class Col extends BaseBlock
{
    protected ?int $xs = null;
    protected ?int $sm = null;
    protected ?int $md = null;
    protected ?int $lg = null;

    /**
     * @return ?int
     */
    public function getXs(): ?int
    {
        return $this->xs;
    }

    /**
     * @param int $common
     * @return Col
     */
    public function xs(int $xs): Col
    {
        $this->xs = $this->validate($xs);
        return $this;
    }

    /**
     * @return ?int
     */
    public function getSm(): ?int
    {
        return $this->sm;
    }

    /**
     * @param int $sm
     * @return Col
     */
    public function sm(int $sm): Col
    {
        $this->sm = $this->validate($sm);
        return $this;
    }

    /**
     * @return ?int
     */
    public function getMd(): ?int
    {
        return $this->md;
    }

    /**
     * @param int $md
     * @return Col
     */
    public function md(int $md): Col
    {
        $this->md = $this->validate($md);
        return $this;
    }

    /**
     * @return ?int
     */
    public function getLg(): ?int
    {
        return $this->lg;
    }

    /**
     * @param int $lg
     * @return Col
     */
    public function lg(int $lg): Col
    {
        $this->lg = $this->validate($lg);
        return $this;
    }

    /**
     * @return string
     */
    public function getColumns(): string
    {
        return
            'col'.($this->getXs()?'-'.$this->getXs():'-12')
            .($this->getSm()?' col-sm-'.$this->getSm():'')
            .($this->getMd()?' col-md-'.$this->getMd():'')
            .($this->getLg()?' col-lg-'.$this->getLg():'');
    }

    private function validate(int $col) : int
    {
        return (1 <= $col && $col <= 12) ? $col : 12;
    }


}
