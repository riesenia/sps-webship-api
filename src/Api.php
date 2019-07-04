<?php
namespace Riesenia\SpsWebship;

/**
 * API client for sending packages
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Api
{
    /** @var SoapClient */
    protected $soap = null;

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    /** @var string */
    protected $wsdl = 'https://webship.sps-sro.sk/services/WebshipWebService?wsdl';

    /** @var string */
    protected $messages = '';

    /**
     * Constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;

        $this->soap = new \SoapClient($this->wsdl);
    }

    /**
     * Call createShipment method.
     *
     * @param array   $shipment
     * @param integer $shipmentType (0 - print waybills, 1 - pickup order)
     *
     * @return bool
     */
    public function createShipment(array $shipment, int $shipmentType = 0): bool
    {
        $response = $this->soap->createShipment([
            'name' => $this->username,
            'password' => $this->password,
            'webServiceShipment' => $shipment,
            'webServiceShipmentType' => $shipmentType
        ]);

        if (isset($response->createShipmentReturn->errors) && $response->createShipmentReturn->errors) {
            $this->messages = (string) $response->createShipmentReturn->errors;

            return false;
        }

        if (isset($response->createShipmentReturn->warnings)) {
            $this->messages = (string) $response->createShipmentReturn->warnings;
        }

        return true;
    }

    /**
     * Call printShipmentLabels method.
     *
     * @return string
     */
    public function printShipmentLabels(): string
    {
        $response = $this->soap->printShipmentLabels([
            'aUserName' => $this->username,
            'aPassword' => $this->password
        ]);

        if (isset($response->printShipmentLabelsReturn->errors) && $response->printShipmentLabelsReturn->errors) {
            $this->messages = (string) $response->printShipmentLabelsReturn->errors;

            return '';
        }

        return $response->printShipmentLabelsReturn->documentUrl;
    }

    /**
     * Call printEndOfDay method.
     *
     * @return string
     */
    public function printEndOfDay(): string
    {
        $response = $this->soap->printEndOfDay([
            'aUserName' => $this->username,
            'aPassword' => $this->password
        ]);

        if (isset($response->printEndOfDayReturn->errors) && $response->printEndOfDayReturn->errors) {
            $this->messages = (string) $response->printEndOfDayReturn->errors;

            return '';
        }

        return $response->printEndOfDayReturn->documentUrl;
    }

    /**
     * Get error messages.
     *
     * @return string
     */
    public function getMessages(): string
    {
        return $this->messages;
    }
}
