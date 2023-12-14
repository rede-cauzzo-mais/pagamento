<?php

namespace RedeCauzzoMais\Pagamento\Contracts\Boleto\Render;

/**
 * Interface Html
 * @package RedeCauzzoMais\Pagamento\Contracts\Boleto\Render
 */
interface Html
{
    /**
     * @param $codigo_barras
     * @return mixed
     */
    public function getImagemCodigoDeBarras($codigo_barras);

    /**
     * @return mixed
     */
    public function gerarBoleto();
}
