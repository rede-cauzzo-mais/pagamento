<?php

namespace RedeCauzzoMais\Pagamento\Contracts;

use RedeCauzzoMais\Pagamento\Contracts\Pessoa as PessoaContract;

/**
 * Interface Conta
 * @package RedeCauzzoMais\Pagamento\Contracts
 */
interface Conta
{
    public function getBanco(): mixed;

    public function getBancoNome(): mixed;

    public function getAgencia(): mixed;

    public function getAgenciaDv(): mixed;

    public function getConta(): mixed;

    public function getContaDv(): mixed;

    public function getPessoa(): PessoaContract;
}
