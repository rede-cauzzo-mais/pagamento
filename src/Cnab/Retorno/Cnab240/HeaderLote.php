<?php

namespace RedeCauzzoMais\Pagamento\Cnab\Retorno\Cnab240;

use Carbon\Carbon;
use RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno\Cnab240\HeaderLote as HeaderLoteContract;
use RedeCauzzoMais\Pagamento\Traits\MagicTrait;

class HeaderLote implements HeaderLoteContract
{
    use MagicTrait;

    protected $codBanco;
    protected $numeroLoteRetorno;
    protected $tipoRegistro;
    protected $tipoOperacao;
    protected $tipoServico;
    protected $versaoLayoutLote;
    protected $tipoInscricao;
    protected $numeroInscricao;
    protected $agenciaDv;
    protected $nomeEmpresa;
    protected $agencia;
    protected $conta;
    protected $contaDv;
    protected $convenio;

    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    public function setTipoRegistro( $tipoRegistro )
    {
        $this->tipoRegistro = $tipoRegistro;

        return $this;
    }

    public function getCodBanco()
    {
        return $this->codBanco;
    }

    public function setCodBanco( $codBanco )
    {
        $this->codBanco = $codBanco;

        return $this;
    }

    public function getNumeroLoteRetorno()
    {
        return $this->numeroLoteRetorno;
    }

    public function setNumeroLoteRetorno( $numeroLoteRetorno )
    {
        $this->numeroLoteRetorno = $numeroLoteRetorno;

        return $this;
    }

    public function getTipoOperacao()
    {
        return $this->tipoOperacao;
    }

    public function setTipoOperacao( $tipoOperacao )
    {
        $this->tipoOperacao = $tipoOperacao;

        return $this;
    }

    public function getTipoServico()
    {
        return $this->tipoServico;
    }

    public function setTipoServico( $tipoServico )
    {
        $this->tipoServico = $tipoServico;

        return $this;
    }

    public function getVersaoLayoutLote()
    {
        return $this->versaoLayoutLote;
    }

    public function setVersaoLayoutLote( $versaoLayoutLote )
    {
        $this->versaoLayoutLote = $versaoLayoutLote;

        return $this;
    }

    public function getTipoInscricao()
    {
        return $this->tipoInscricao;
    }

    public function setTipoInscricao( $tipoInscricao )
    {
        $this->tipoInscricao = $tipoInscricao;

        return $this;
    }

    public function getNumeroInscricao()
    {
        return $this->numeroInscricao;
    }

    public function setNumeroInscricao( $numeroInscricao )
    {
        $this->numeroInscricao = $numeroInscricao;

        return $this;
    }

    public function getConvenio()
    {
        return $this->convenio;
    }

    public function setConvenio( $convenio )
    {
        $this->convenio = $convenio;

        return $this;
    }

    public function getNomeEmpresa()
    {
        return $this->nomeEmpresa;
    }

    public function setNomeEmpresa( $nomeEmpresa )
    {
        $this->nomeEmpresa = $nomeEmpresa;

        return $this;
    }

    public function getAgencia()
    {
        return $this->agencia;
    }

    public function setAgencia( $agencia )
    {
        $this->agencia = $agencia;

        return $this;
    }

    public function getAgenciaDv()
    {
        return $this->agenciaDv;
    }

    public function setAgenciaDv( $agenciaDv )
    {
        $this->agenciaDv = $agenciaDv;

        return $this;
    }

    public function getConta()
    {
        return $this->conta;
    }

    public function setConta( $conta )
    {
        $this->conta = $conta;

        return $this;
    }

    public function getContaDv()
    {
        return $this->contaDv;
    }

    public function setContaDv( $contaDv )
    {
        $this->contaDv = $contaDv;

        return $this;
    }
}
