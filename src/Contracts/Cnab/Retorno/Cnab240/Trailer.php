<?php

namespace RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno\Cnab240;

interface Trailer
{
    public function getTipoRegistro();

    public function getNumeroLote();

    public function getQtdLotesArquivo();

    public function getQtdRegistroArquivo();

    public function toArray(): array;
}
