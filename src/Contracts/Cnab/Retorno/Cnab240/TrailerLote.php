<?php

namespace RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno\Cnab240;

interface TrailerLote
{
    public function getLoteServico();

    public function getQtdRegistroLote();

    public function getTipoRegistro();

    public function getValorTotalTitulos();

    public function toArray(): array;
}
