<?php
namespace Prochito\Siampay\Action;

use Http\Message\MessageFactory\GuzzleMessageFactory;
use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
use Prochito\Siampay\Api;

class CaptureAction extends GatewayAwareAction
{
    private $api;

    /**
     * {@inheritDoc}
     */
    public function setApi($api)
    {
        if (false == $api instanceof Api) {
            throw new UnsupportedApiException(sprintf('Not supported. Expected %s instance to be set as api.', Api::class));
        }
        $this->api = $api;
    }

    /**
     * {@inheritDoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        // $this->gateway->execute($request);
//        $this->api = new Api($model, $this->createHttpClientMock(), $this->createHttpMessageFactory());

        return $this->api->payment();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
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
