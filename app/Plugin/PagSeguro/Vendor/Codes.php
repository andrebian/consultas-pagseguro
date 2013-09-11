<?php
/**
* Plugin de integração com a API do PagSeguro e CakePHP (API de verdade, não somente montar a string e enviar por post).
*
* Versão do PHP 5.*
*
*
* @author Andre Cardoso <andrecardosodev@gmail.com>
* @link https://github.com/andrebian/cake-plugin-pagseguro/
* @authorURI http://andrebian.com
* @license MIT (http://opensource.org/licenses/MIT)
* @version 1.3
* @since 1.3 
* 
* ESTE PLUGIN UTILIZA A API DO PAGSEGURO, DISPONÍVEL EM  https://pagseguro.uol.com.br/v2/guia-de-integracao/tutorial-da-biblioteca-pagseguro-em-php.html
* 
* 
* 
* PLUGIN BASE (enviando dados via POST):
*   https://github.com/ftgoncalves/pagseguro/  de Felipe Theodoro Gonçalves, (http://ftgoncalves.com.br)
*/

class Codes {
    
   
 /**
  * obterTipoTransacao method
  * 
  * @param int $codigo
  * @return string
  */   
    public static function obterTipoTransacao($codigo) {
        switch ($codigo) {
            case 1:
                $tipo = 'Pagamento';
                break;
            case 2:
                $tipo = 'Transferência';
                break;
            case 3:
                $tipo = 'Adição de fundos'; // confirmar
                break;
            case 4:
                $tipo = 'Saque';
                break;
            case 5: 
                $tipo = 'Recarga'; // confirmar
                break;
            case 6:
                $tipo = 'Doação';
                break;
            case 7: 
                $tipo = 'Bônus';
                break;
            case 8:
                $tipo = 'Repasse de bônus'; // confirmar
                break;
            case 9: 
                $tipo = 'Operacional'; // confirmar
                break;
            case 10:
                $tipo = 'Doação política';
                break;
            default: 
                $tipo = 'Não especificado';
                break;
        }
        return $tipo;
    }

    
/**
  * Retorna em modo de array ou string o status da transação pesquisada
  * 
  * @param int codigo 
  * @return array
  * @since 1.2
  */   
    public static function obterStatusTransacao($codigo, $tipoRetorno = '') {
            switch($codigo) {
               case 1:
                   $statusRetorno = array('id' => 1, 'descricao' => 'Aguardando pagamento');
                   break;
               case 2:
                   $statusRetorno = array('id' => 2, 'descricao' => 'Em análise');
                   break;
               case 3:
                   $statusRetorno = array('id' => 3, 'descricao' => 'Paga');
                   break;
               case 4:
                   $statusRetorno = array('id' => 4, 'descricao' => 'Disponível');
                   break;
               case 5:
                   $statusRetorno = array('id' => 5, 'descricao' => 'Em disputa');
                   break;
               case 6:
                   $statusRetorno = array('id' => 6, 'descricao' => 'Devolvida');
                   break;
               case 7:
                   $statusRetorno = array('id' => 7, 'descricao' => 'Cancelada');
                   break;
               default:
                   $statusRetorno = array('id' => 0, 'descricao' => 'Não foi possível obter o status');
                   break;
            }

            if (is_array($tipoRetorno)) {
                return $statusRetorno;
            } else {
                return $statusRetorno['descricao'];
            }
    }
    
    
 /**
  * Retorna o tipo de pagamento selecionado pelo comprador
  * 
  * @param int $codigo
  * @return string
  */   
    public static function obterTipoPagamento($codigo) {
        switch($codigo) {
            case 1:
                $tipo = 'Cartão de crédito';
                break;
            case 2:
                $tipo = 'Boleto';
                break;
            case 3:
                $tipo = 'Débito online (TEF)';
                break;
            case 4:
                $tipo = 'Saldo PagSeguro';
                break;
            case 5:
                $tipo = 'Oi Paggo';
                break;
            default:
                $tipo = 'Informação não disponível';
                break;
        }
        return $tipo;
    }
    
    
 /**
  * Retorna o meio de pagamento selecionado pelo comprador
  * 
  * @param int $codigo
  * @return string
  */   
    public static function obterMeioPagamento($codigo) {
        switch($codigo) {
            case 101:
                $meio = 'Cartão de crédito Visa';
                break;
            case 102:
                $meio = 'Cartão de crédito MasterCard';
                break;
            case 103:
                $meio = 'Cartão de crédito American Express';
                break;
            case 104:
                $meio = 'Cartão de crédito Dinners';
                break;
            case 105:
                $meio = 'Cartão de crédito Hypercard';
                break;
            case 106:
                $meio = 'Cartão de crédito Aura';
                break;
            case 107:
                $meio = 'Cartão de crédito Elo';
                break;
            case 108:
                $meio = 'Cartão de crédito PLENOCard';
                break;
            case 109:
                $meio = 'Cartão de crédito PersonalCard';
                break;
            case 110:
                $meio = 'Cartão de crédito JCB';
                break;
            case 111:
                $meio = 'Cartão de crédito Discover';
                break;
            case 112:
                $meio = 'Cartão de crédito BrasilCard';
                break;
            case 113:
                $meio = 'Cartão de crédito FORTBRASIL';
                break;
            case 202:
                $meio = 'Boleto Santander';
                break;
            case 301:
                $meio = 'Débito Online Bradesco';
                break;
            case 302:
                $meio = 'Débito Online Itaú';
                break;
            case 304:
                $meio = 'Débito Online Banco do Brasil';
                break;
            case 306:
                $meio = 'Débito Online Banrisul';
                break;
            case 307:
                $meio = 'Débito Online HSBC';
                break;
            case 401:
                $meio = 'Saldo PagSeguro';
                break;
            case 501:
                $meio = 'Oi Paggo';
                break;
        }
        return $meio;
    }
    
}

?>
