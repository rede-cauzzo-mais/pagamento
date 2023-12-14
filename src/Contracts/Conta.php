<?php

namespace RedeCauzzoMais\Pagamento\Contracts;

use RedeCauzzoMais\Pagamento\Contracts\Pessoa as PessoaContract;

/**
 * Interface Conta
 * @package RedeCauzzoMais\Pagamento\Contracts
 */
interface Conta
{
    /**
     * @return mixed
     */
    public function getBanco();

    /**
     * @return mixed
     */
    public function getBancoNome();

    /**
     * @return mixed
     */
    public function getAgencia();

    /**
     * @return mixed
     */
    public function getAgenciaDv();

    /**
     * @return mixed
     */
    public function getConta();

    /**
     * @return mixed
     */
    public function getContaDv();

    /**
     * @return PessoaContract
     */
    public function getPessoa();

}
