<?php

/**
 * Created by PhpStorm.
 * User: rahit
 * Date: 8/11/16
 * Time: 11:12 PM
 */
class TestCaptureAction extends Payum\Core\Tests\Action\CapturePaymentActionTest{


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
        ), $this->createHttpClientMock(), $this->createHttpMessageFactory());
        $params['HASH'] = $api->calculateHash($params);
        $this->assertTrue($api->verifyHash($params));
        
    }
}