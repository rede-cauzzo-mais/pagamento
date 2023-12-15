<?php

namespace RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno\Cnab240;

interface HeaderLote
{
    public function getTipoRegistro();

    public function getTipoOperacao();

    public function getTipoServico();

    public function getVersaoLayoutLote();

    public function getCodBanco();

    public function getTipoInscricao();

    public function getNumeroInscricao();

    public function getNumeroLoteRetorno();

    public function getConvenio();

    public function getNomeEmpresa();

    public function getAgencia();

    public function getAgenciaDv();

    public function getConta();

    public function getContaDv();

    public function toArray(): array;
}
