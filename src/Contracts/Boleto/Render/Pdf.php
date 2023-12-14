<?php

namespace RedeCauzzoMais\Pagamento\Contracts\Boleto\Render;

/**
 * Interface Pdf
 * @package RedeCauzzoMais\Pagamento\Contracts\Boleto\Render
 */
interface Pdf
{
    /**
     * @param $dest
     * @param null $save_path
     * @return mixed
     */
    public function gerarBoleto($dest = self::OUTPUT_STANDARD, $save_path = null);
}
