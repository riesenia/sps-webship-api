<?php
namespace Riesenia\SpsWebship;

/**
 * API client for getting shipment status.
 *
 * @author Tomas Saghy <segy@riesenia.com>
 */
class Tracker
{
    /** @var int */
    protected $customer;

    /** @var int */
    protected $customerType;

    /** @var string */
    protected $language;

    /** @var string */
    protected $wsdl = 'http://t-t.sps-sro.sk/service_soap.php?wsdl';

    /**
     * Constructor.
     *
     * @param string $language
     * @param int    $customer
     * @param int    $customerType
     */
    public function __construct(string $language, int $customer, int $customerType = 1)
    {
        $this->language = $language;
        $this->customer = $customer;
        $this->customerType = $customerType;

        $this->soap = new \SoapClient($this->wsdl);
    }

    /**
     * Get shipment by shipment number.
     *
     * @param string $number
     *
     * @return \stdClass
     */
    public function getShipment(string $number): \stdClass
    {
        if (!\preg_match('/([0-9]{3})-([0-9]{3})-([0-9]+)/', $number, $m)) {
            throw new \InvalidArgumentException('Invalid shipment number format!');
        }

        try {
            $response = $this->soap->__call('getShipment', [
                'landnr' => $m[1],
                'mandnr' => $m[2],
                'lfdnr' => $m[3],
                'langi' => $this->language
            ]);
        } catch (\SoapFault $e) {
            return [];
        }

        return $response;
    }

    /**
     * Get shipments by reference number.
     *
     * @param string $reference
     * @param string $date
     *
     * @return \stdClass[]
     */
    public function getShipments(string $reference, string $date = ''): array
    {
        try {
            $response = $this->soap->__call('getListOfShipments', [
                'kundenr' => $this->customer,
                'verknr' => $reference,
                'km_mandr' => $this->customerType,
                'versdat' => $date,
                'langi' => $this->language
            ]);
        } catch (\SoapFault $e) {
            return [];
        }

        return (array) $response;
    }
}
