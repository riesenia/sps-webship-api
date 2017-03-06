<?php
namespace Riesenia\SpsWebship;

/**
 * Class Client
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Api
{
    /** @var null|SoapClient */
    private $soap = null;

    /** @var null|string */
    private $username;

    /** @var null|string */
    private $password;

    /** @var string */
    private $wsdl = 'https://webship.sps-sro.sk/services/WebshipWebService?wsdl';

    /**
     * MyApi constructor
     *
     * @param null|string $username
     * @param null|string $password
     * @param null|integer $customerId
     */
    public function __construct($username = null, $password = null)
    {
        $this->username = $username;
        $this->password = $password;

        try {
            $this->soap = new \SoapClient($this->wsdl);
        } catch (\Exception $e) {
            throw new \Exception('Failed to build soap client');
        }
    }
}