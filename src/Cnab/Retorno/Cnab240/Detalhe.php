<?php

namespace RedeCauzzoMais\Pagamento\Cnab\Retorno\Cnab240;

use Carbon\Carbon;
use RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno\Cnab240\Detalhe as DetalheContract;
use RedeCauzzoMais\Pagamento\Contracts\Pessoa as PessoaContract;
use RedeCauzzoMais\Pagamento\Contracts\Conta as ContaContract;
use RedeCauzzoMais\Pagamento\Traits\MagicTrait;
use RedeCauzzoMais\Pagamento\Util;

class Detalhe implements DetalheContract
{
    use MagicTrait;

    protected $ocorrencia;
    protected $ocorrenciaTipo;
    protected $ocorrenciaDescricao;
    protected $numeroControle;
    protected $numeroDocumento;
    protected $nossoNumero;
    protected $carteira;
    protected $dataVencimento;
    protected $dataOcorrencia;
    protected $dataCredito;
    protected $valor;
    protected $valorRecebido;
    protected $valorIOF;
    protected $valorAbatimento;
    protected $valorDesconto;
    protected $valorMora;
    protected $valorMulta;
    protected $pagador;
    protected $favorecido;
    protected $contaFavorecido;
    protected $contaPagador;
    protected $cheques = [];
    protected $error;

    public function getOcorrencia()
    {
        return $this->ocorrencia;
    }

    public function hasOcorrencia(): bool
    {
        $ocorrencias = func_get_args();

        if ( count( $ocorrencias ) == 0 && !empty( $this->getOcorrencia() ) ) {
            return true;
        }

        if ( count( $ocorrencias ) == 1 && is_array( func_get_arg( 0 ) ) ) {
            $ocorrencias = func_get_arg( 0 );
        }

        if ( in_array( $this->getOcorrencia(), $ocorrencias ) ) {
            return true;
        }

        return false;
    }

    public function setOcorrencia( $ocorrencia )
    {
        $this->ocorrencia = $ocorrencia;

        return $this;
    }

    public function getOcorrenciaTipo()
    {
        return $this->ocorrenciaTipo;
    }

    public function setOcorrenciaTipo( $ocorrenciaTipo )
    {
        $this->ocorrenciaTipo = $ocorrenciaTipo;

        return $this;
    }

    public function getOcorrenciaDescricao()
    {
        return $this->ocorrenciaDescricao;
    }

    public function setOcorrenciaDescricao( $ocorrenciaDescricao )
    {
        $this->ocorrenciaDescricao = $ocorrenciaDescricao;

        return $this;
    }

    public function getNumeroDocumento()
    {
        return $this->numeroDocumento;
    }

    public function setNumeroDocumento( $numeroDocumento )
    {
        $this->numeroDocumento = $numeroDocumento;

        return $this;
    }

    public function getNossoNumero()
    {
        return $this->nossoNumero;
    }

    public function setNossoNumero( $nossoNumero )
    {
        $this->nossoNumero = $nossoNumero;

        return $this;
    }

    public function getDataVencimento( $format = 'd/m/Y' )
    {
        return $this->dataVencimento instanceof Carbon ? ( $format === false ? $this->dataVencimento : $this->dataVencimento->format( $format ) ) : null;
    }

    public function setDataVencimento( $dataVencimento, $format = 'dmY' )
    {
        $this->dataVencimento = trim( $dataVencimento, '0 ' ) ? Carbon::createFromFormat( $format, $dataVencimento ) : null;

        return $this;
    }

    public function getDataCredito( $format = 'd/m/Y' )
    {
        return $this->dataCredito instanceof Carbon ? ( $format === false ? $this->dataCredito : $this->dataCredito->format( $format ) ) : null;
    }

    public function setDataCredito( $dataCredito, $format = 'dmY' )
    {
        $this->dataCredito = trim( $dataCredito, '0 ' ) ? Carbon::createFromFormat( $format, $dataCredito ) : null;

        return $this;
    }

    public function getDataOcorrencia( $format = 'd/m/Y' )
    {
        return $this->dataOcorrencia instanceof Carbon ? ( $format === false ? $this->dataOcorrencia : $this->dataOcorrencia->format( $format ) ) : null;
    }

    public function setDataOcorrencia( $dataOcorrencia, $format = 'dmY' )
    {
        $this->dataOcorrencia = trim( $dataOcorrencia, '0 ' ) ? Carbon::createFromFormat( $format, $dataOcorrencia ) : null;

        return $this;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValor( $valor )
    {
        $this->valor = $valor;

        return $this;
    }

    public function getValorIOF()
    {
        return $this->valorIOF;
    }

    public function setValorIOF( $valorIOF )
    {
        $this->valorIOF = $valorIOF;

        return $this;
    }

    public function getValorAbatimento()
    {
        return $this->valorAbatimento;
    }

    public function setValorAbatimento( $valorAbatimento )
    {
        $this->valorAbatimento = $valorAbatimento;

        return $this;
    }

    public function getValorDesconto()
    {
        return $this->valorDesconto;
    }

    public function setValorDesconto( $valorDesconto )
    {
        $this->valorDesconto = $valorDesconto;

        return $this;
    }

    public function getValorMora()
    {
        return $this->valorMora;
    }

    public function setValorMora( $valorMora )
    {
        $this->valorMora = $valorMora;

        return $this;
    }

    public function getValorMulta()
    {
        return $this->valorMulta;
    }

    public function setValorMulta( $valorMulta )
    {
        $this->valorMulta = $valorMulta;

        return $this;
    }

    public function getValorRecebido()
    {
        return $this->valorRecebido;
    }

    public function setValorRecebido( $valorRecebido )
    {
        $this->valorRecebido = $valorRecebido;

        return $this;
    }

    public function getPagador(): PessoaContract
    {
        return $this->pagador;
    }

    public function setPagador( $pagador )
    {
        Util::addPessoa( $this->pagador, $pagador );

        return $this;
    }

    public function getFavorecido(): PessoaContract
    {
        return $this->favorecido;
    }

    public function setFavorecido( $favorecido )
    {
        Util::addPessoa( $this->favorecido, $favorecido );

        return $this;
    }

    public function getContaPagador(): ContaContract
    {
        return $this->contaPagador;
    }

    public function setContaPagador( $contaPagador )
    {
        Util::addConta( $this->contaPagador, $contaPagador );

        return $this;
    }

    public function getContaFavorecido(): ContaContract
    {
        return $this->contaFavorecido;
    }

    public function setContaFavorecido( $contaFavorecido )
    {
        Util::addConta( $this->contaFavorecido, $contaFavorecido );

        return $this;
    }

    public function hasError(): bool
    {
        return $this->getOcorrencia() == self::OCORRENCIA_ERRO;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setError( $error )
    {
        $this->ocorrenciaTipo = self::OCORRENCIA_ERRO;
        $this->error          = $error;

        return $this;
    }
}
