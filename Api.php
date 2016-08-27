<?php
namespace Prochito\Siampay;

use Http\Message\MessageFactory;
use Payum\Core\Exception\Http\HttpException;
use Payum\Core\HttpClientInterface;

class Api
{
    /**
     * @var HttpClientInterfacecd
     */
    protected $client;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param array               $options
     * @param HttpClientInterface $client
     * @param MessageFactory      $messageFactory
     *
     * @throws \Payum\Core\Exception\InvalidArgumentException if an option is invalid
     */
    public function __construct(array $options, HttpClientInterface $client, MessageFactory $messageFactory)
    {
        $this->options = $options;
        $this->client = $client;
        $this->messageFactory = $messageFactory;
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    protected function doRequest($method, array $fields)
    {
        $headers = [];

        $request = $this->messageFactory->createRequest($method, $this->getApiEndpoint(), $headers, http_build_query($fields));
        var_dump($request);
        $response = $this->client->send($request);

        if (false == ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300)) {
            throw HttpException::factory($request, $response);
        }

        return $response;
    }

    /**
     * @return array
     */
    public function payment(){
        return $this->doRequest('POST', $this->options);
    }

    /**
     * @return string
     */
    protected function getApiEndpoint()
    {
        return $this->options['sandbox'] ? 'https://test.siampay.com/b2cDemo/eng/payment/payForm.jsp' : 'https://www.siampay.com/b2c2/eng/payment/payForm.jsp';
    }

    /**
     * @param  array $params
     */
    protected function addGlobalParams(array &$params)
    {
//        $params['VERSION'] = self::VERSION;
        $params['IDENTIFIER'] = $this->options['identifier'];
        $params['HASH'] = $this->calculateHash($params);
    }

    /**
     * @param array $params
     * @return string
     */
    public function calculateHash(array $params){
        ksort($params);
        $clearString = $this->options['password'];
        foreach ($params as $key => $value) {
            $clearString .= $key.'='.$value.$this->options['password'];
        }
        return hash('sha256', $clearString);
    }

    /**
     * @param array $params
     * @return bool
     */
    public function verifyHash(array $params){
        if (empty($params['HASH'])) {
            return false;
        }
        $hash = $params['HASH'];
        unset($params['HASH']);
        return $hash === $this->calculateHash($params);
    }
}
