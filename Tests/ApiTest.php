<?php
namespace Prochito\Siampay\Tests;

use Http\Message\MessageFactory\GuzzleMessageFactory;
use Prochito\Siampay\Api;


/**
 * Created by PhpStorm.
 * User: rahit
 * Date: 8/11/16
 * Time: 11:12 PM
 */


class ApiTest extends \PHPUnit_Framework_TestCase{

    public function testExecute() {
        //$model = Payum\Core\Model\Payment::
        $params = array(
            'secureHash' => '44f3760c201d3688440f62497736bfa2aadd1bc0'
        );
        $api = new Api(array(
            'merchantId' => '76063508',
            'amount' => 10000,
            'orderRef' => 001,
            'currCode' => 764,
            'mpsMode' => 'MCP',
            'successUrl' => 'https://www.youtube.com/',
            'failUrl' => 'http://its.prochito.com/',
            'cancelUrl' => 'https://wwww.github.com/prochito',
            'payType' => 'N',
            'lang' => 'E',
            'payMethod' => 'ALL',
            'sandbox' => true,
            'password' => 'Mypassword'
        ), $this->createHttpClientMock(), $this->createHttpMessageFactory());
        $params['HASH'] = $api->calculateHash($params);
        $this->assertTrue($api->verifyHash($params));

    }

    /**
     * @return array
     */
    public function testPayment(){
        $api = new Api(array(
            'merchantId' => '76063508',
            'amount' => 10000,
            'orderRef' => 001,
            'currCode' => 764,
            'mpsMode' => 'MCP',
            'successUrl' => 'https://www.youtube.com/',
            'failUrl' => 'http://its.prochito.com/',
            'cancelUrl' => 'https://wwww.github.com/prochito',
            'payType' => 'N',
            'lang' => 'E',
            'payMethod' => 'ALL',
            'sandbox' => true,
            'secureHash' => '44f3760c201d3688440f62497736bfa2aadd1bc0',
//            'password' => 'want2Believe'
        ), $this->createHttpClientMock(), $this->createHttpMessageFactory());

        $this->assertTrue($api->payment());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|HttpClientInterface
     */
    protected function createHttpClientMock()
    {
        return $this->getMock('Payum\Core\HttpClientInterface');
    }
    /**
     * @return \Http\Message\MessageFactory
     */
    protected function createHttpMessageFactory()
    {
        return new GuzzleMessageFactory();
    }

}