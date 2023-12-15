<?php

namespace RedeCauzzoMais\Pagamento\Cnab\Retorno\Cnab240;

use RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno\Cnab240\TrailerLote as TrailerLoteContract;
use RedeCauzzoMais\Pagamento\Traits\MagicTrait;

class TrailerLote implements TrailerLoteContract
{
    use MagicTrait;

    protected $loteServico;
    protected $TipoRegistro;
    protected $qtdRegistroLote;
    protected $valorTotalTitulos;

    public function getLoteServico(): ?int
    {
        return $this->loteServico;
    }

    public function setLoteServico( int $loteServico ): self
    {
        $this->loteServico = $loteServico;

        return $this;
    }

    public function getQtdRegistroLote(): ?int
    {
        return $this->qtdRegistroLote;
    }

    public function setQtdRegistroLote( int $qtdRegistroLote ): self
    {
        $this->qtdRegistroLote = $qtdRegistroLote;

        return $this;
    }

    public function getTipoRegistro(): ?int
    {
        return $this->TipoRegistro;
    }

    public function setTipoRegistro( int $TipoRegistro ): self
    {
        $this->TipoRegistro = $TipoRegistro;

        return $this;
    }

    public function getValorTotalTitulos(): ?float
    {
        return $this->valorTotalTitulos;
    }

    public function setValorTotalTitulos( float $valorTotalTitulos ): self
    {
        $this->valorTotalTitulos = $valorTotalTitulos;

        return $this;
    }
}
