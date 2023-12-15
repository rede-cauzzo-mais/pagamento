<?php

namespace RedeCauzzoMais\Pagamento\Cnab\Remessa\Cnab240;

use Exception;
use RedeCauzzoMais\Pagamento\Cnab\Remessa\AbstractRemessa as AbstractRemessaGeneric;

abstract class AbstractRemessa extends AbstractRemessaGeneric
{
    protected $tamanho_linha = 240;

    protected $aRegistros = [
        self::HEADER       => [],
        self::HEADER_LOTE  => [],
        self::DETALHE      => [],
        self::TRAILER_LOTE => [],
        self::TRAILER      => [],
    ];

    abstract protected function headerLote();

    abstract protected function trailerLote();

    protected function getHeaderLote()
    {
        return $this->aRegistros[self::HEADER_LOTE];
    }

    protected function getTrailerLote()
    {
        return $this->aRegistros[self::TRAILER_LOTE];
    }

    protected function iniciaHeader()
    {
        $this->aRegistros[self::HEADER] = array_fill(0, 240, ' ');
        $this->atual                    = &$this->aRegistros[self::HEADER];
    }

    protected function iniciaHeaderLote()
    {
        $this->aRegistros[self::HEADER_LOTE] = array_fill(0, 240, ' ');
        $this->atual                         = &$this->aRegistros[self::HEADER_LOTE];
    }

    protected function iniciaTrailerLote()
    {
        $this->aRegistros[self::TRAILER_LOTE] = array_fill(0, 240, ' ');
        $this->atual                          = &$this->aRegistros[self::TRAILER_LOTE];
    }

    protected function iniciaTrailer()
    {
        $this->aRegistros[self::TRAILER] = array_fill(0, 240, ' ');
        $this->atual                     = &$this->aRegistros[self::TRAILER];
    }

    protected function iniciaDetalhe()
    {
        $this->iRegistros++;
        $this->aRegistros[self::DETALHE][$this->iRegistros] = array_fill(0, 240, ' ');
        $this->atual                                        = &$this->aRegistros[self::DETALHE][$this->iRegistros];
    }

    public function gerar()
    {
        $errors = '';
        if (!$this->isValid($errors)) {
            throw new Exception('Campos requeridos pelo banco, aparentam estar ausentes: ' . $errors);
        }

        $stringRemessa = '';
        if ($this->iRegistros < 1) {
            throw new Exception('Nenhuma linha detalhe foi adicionada');
        }

        $this->header();
        $stringRemessa .= $this->valida($this->getHeader()) . $this->fimLinha;

        $this->headerLote();
        $stringRemessa .= $this->valida($this->getHeaderLote()) . $this->fimLinha;

        foreach ($this->getDetalhes() as $i => $detalhe) {
            $stringRemessa .= $this->valida($detalhe) . $this->fimLinha;
        }

        $this->trailerLote();
        $stringRemessa .= $this->valida($this->getTrailerLote()) . $this->fimLinha;

        $this->trailer();
        $stringRemessa .= $this->valida($this->getTrailer()) . $this->fimArquivo;

        return $stringRemessa;
    }
}
