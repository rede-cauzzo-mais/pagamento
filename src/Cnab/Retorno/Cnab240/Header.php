<?php

namespace RedeCauzzoMais\Pagamento\Cnab\Retorno\Cnab240;

use Carbon\Carbon;
use RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno\Cnab240\Header as HeaderContract;
use RedeCauzzoMais\Pagamento\Traits\MagicTrait;

class Header implements HeaderContract
{
    use MagicTrait;

    protected $codBanco;
    protected $nomeBanco;
    protected $codigoRemessaRetorno;
    protected $loteServico;
    protected $tipoRegistro;
    protected $data;
    protected $tipoInscricao;
    protected $agencia;
    protected $agenciaDv;
    protected $nomeEmpresa;
    protected $documentoEmpresa;
    protected $numeroSequencialArquivo;
    protected $versaoLayoutArquivo;
    protected $numeroInscricao;
    protected $conta;
    protected $contaDv;
    protected $codigoCedente;
    protected $convenio;

    public function getLoteServico(): string
    {
        return $this->loteServico;
    }

    public function setLoteServico( $loteServico ): self
    {
        $this->loteServico = $loteServico;

        return $this;
    }

    public function getTipoRegistro(): string
    {
        return $this->tipoRegistro;
    }

    public function setTipoRegistro( $tipoRegistro ): self
    {
        $this->tipoRegistro = $tipoRegistro;

        return $this;
    }

    public function getTipoInscricao(): string
    {
        return $this->tipoInscricao;
    }

    public function setTipoInscricao( $tipoInscricao ): self
    {
        $this->tipoInscricao = $tipoInscricao;

        return $this;
    }

    public function getAgencia(): string
    {
        return $this->agencia;
    }

    public function setAgencia( $agencia ): self
    {
        $this->agencia = ltrim( trim( $agencia, ' ' ), '0' );

        return $this;
    }

    public function getAgenciaDv(): string
    {
        return $this->agenciaDv;
    }

    public function setAgenciaDv( $agenciaDv ): self
    {
        $this->agenciaDv = $agenciaDv;

        return $this;
    }

    public function getNomeEmpresa(): string
    {
        return $this->nomeEmpresa;
    }

    public function setNomeEmpresa( $nomeEmpresa ): self
    {
        $this->nomeEmpresa = $nomeEmpresa;

        return $this;
    }

    public function getDocumentoEmpresa(): string
    {
        return $this->documentoEmpresa;
    }

    public function setDocumentoEmpresa( $documentoEmpresa ): self
    {
        $this->documentoEmpresa = $documentoEmpresa;

        return $this;
    }

    public function getNumeroSequencialArquivo(): string
    {
        return $this->numeroSequencialArquivo;
    }

    public function setNumeroSequencialArquivo( $numeroSequencialArquivo ): self
    {
        $this->numeroSequencialArquivo = $numeroSequencialArquivo;

        return $this;
    }

    public function getVersaoLayoutArquivo(): string
    {
        return $this->versaoLayoutArquivo;
    }

    public function setVersaoLayoutArquivo( $versaoLayoutArquivo ): self
    {
        $this->versaoLayoutArquivo = $versaoLayoutArquivo;

        return $this;
    }

    public function getNumeroInscricao(): string
    {
        return $this->numeroInscricao;
    }

    public function setNumeroInscricao( $numeroInscricao ): self
    {
        $this->numeroInscricao = $numeroInscricao;

        return $this;
    }

    public function getConta(): string
    {
        return $this->conta;
    }

    public function setConta( $conta ): self
    {
        $this->conta = ltrim( trim( $conta, ' ' ), '0' );

        return $this;
    }

    public function getContaDv(): string
    {
        return $this->contaDv;
    }

    public function setContaDv( $contaDv ): self
    {
        $this->contaDv = $contaDv;

        return $this;
    }

    public function getCodigoCedente(): string
    {
        return $this->codigoCedente;
    }

    public function setCodigoCedente( $codigoCedente ): self
    {
        $this->codigoCedente = $codigoCedente;

        return $this;
    }

    public function getData( $format = 'd/m/Y' ): ?string
    {
        return $this->data instanceof Carbon ? ( $format === false ? $this->data : $this->data->format( $format ) ) : null;
    }

    public function setData( $data, $format = 'dmY' ): self
    {
        $this->data = trim( $data, '0 ' ) ? Carbon::createFromFormat( $format, $data ) : null;

        return $this;
    }

    public function getConvenio(): string
    {
        return $this->convenio;
    }

    public function setConvenio( $convenio ): self
    {
        $this->convenio = $convenio;

        return $this;
    }

    public function getCodBanco(): int
    {
        return $this->codBanco;
    }

    public function setCodBanco( $codBanco ): self
    {
        $this->codBanco = $codBanco;

        return $this;
    }

    public function getCodigoRemessaRetorno(): int
    {
        return $this->codigoRemessaRetorno;
    }

    public function setCodigoRemessaRetorno( $codigoRemessaRetorno ): self
    {
        $this->codigoRemessaRetorno = $codigoRemessaRetorno;

        return $this;
    }

    public function getNomeBanco(): string
    {
        return $this->nomeBanco;
    }

    public function setNomeBanco( $nomeBanco ): self
    {
        $this->nomeBanco = $nomeBanco;

        return $this;
    }
}
