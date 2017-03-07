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
    protected $soap = null;

    /** @var null|string */
    protected $username;

    /** @var null|string */
    protected $password;

    /** @var string */
    protected $wsdl = 'https://webship.sps-sro.sk/services/WebshipWebService?wsdl';

    /** @var null|string */
    protected $messages;

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

    /**
     * Call createShipment method
     *
     * @param array $shipment
     * @param integer $shipmentType (0 - print waybills, 1 - pickup order)
     * @return boolean
     */
    public function createShipment(array $shipment, $shipmentType = 0)
    {
        $response = $this->soap->createShipment([
            'name' => $this->username,
            'password' => $this->password,
            'webServiceShipment' => $shipment,
            'webServiceShipmentType' => $shipmentType
        ]);

        if (isset($response->createShipmentReturn->errors) && $response->createShipmentReturn->errors) {
            $this->messages = $response->createShipmentReturn->errors;
            return false;
        }

        if (isset($response->createShipmentReturn->warnings)) {
            $this->messages = $response->createShipmentReturn->warnings;
        }

        return true;
    }

    /**
     * Call printShipmentLabels method
     *
     * @return string|boolean
     */
    public function printShipmentLabels()
    {
        $response = $this->soap->printShipmentLabels([
            'aUserName' => $this->username,
            'aPassword' => $this->password
        ]);

        if (isset($response->printShipmentLabelsReturn->errors) && $response->printShipmentLabelsReturn->errors) {
            $this->messages = $response->printShipmentLabelsReturn->errors;
            return false;
        }

        return $response->printShipmentLabelsReturn->documentUrl;
    }

    /**
     * Call printEndOfDay method
     *
     * @return string|boolean
     */
    public function printEndOfDay()
    {
        $response = $this->soap->printEndOfDay([
            'aUserName' => $this->username,
            'aPassword' => $this->password
        ]);

        if (isset($response->printEndOfDayReturn->errors) && $response->printEndOfDayReturn->errors) {
            $this->messages = $response->printEndOfDayReturn->errors;
            return false;
        }

        return $response->printEndOfDayReturn->documentUrl;
    }

    /**
     * get response messages
     *
     * @return string|null
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
