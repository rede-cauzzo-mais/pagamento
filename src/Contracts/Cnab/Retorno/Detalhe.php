<?php

namespace RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno;

use RedeCauzzoMais\Pagamento\Contracts\Pessoa as PessoaContract;
use RedeCauzzoMais\Pagamento\Contracts\Conta as ContaContract;

interface Detalhe
{
    const OCORRENCIA_LIQUIDADA  = 1;
    const OCORRENCIA_BAIXADA    = 2;
    const OCORRENCIA_ENTRADA    = 3;
    const OCORRENCIA_ALTERACAO  = 4;
    const OCORRENCIA_PROTESTADA = 5;
    const OCORRENCIA_OUTROS     = 6;
    const OCORRENCIA_ERRO       = 9;

    public function getFavorecido(): PessoaContract;

    public function getPagador(): PessoaContract;

    public function getNossoNumero();

    public function getNumeroDocumento();

    public function getOcorrencia();

    public function getOcorrenciaDescricao();

    public function getOcorrenciaTipo();

    public function getDataOcorrencia( string $format = 'd/m/Y' );

    public function getDataVencimento( string $format = 'd/m/Y' );

    public function getDataCredito( string $format = 'd/m/Y' );

    public function getValor();

    public function getValorIOF();

    public function getValorAbatimento();

    public function getValorDesconto();

    public function getValorRecebido();

    public function getValorMora();

    public function getValorMulta();

    public function getContaFavorecido(): ContaContract;

    public function getContaPagador(): ContaContract;

    public function getError(): string;

    public function hasError(): bool;

    public function hasOcorrencia(): bool;

    public function toArray(): array;
}
