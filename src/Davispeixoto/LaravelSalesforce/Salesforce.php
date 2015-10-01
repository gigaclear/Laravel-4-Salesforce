<?php namespace Davispeixoto\LaravelSalesforce;

use Davispeixoto\ForceDotComToolkitForPhp\SforceEnterpriseClient as Client;
use Exception;
use Illuminate\Config\Repository;

/**
 * Class Salesforce
 *
 * Provides an easy binding to Salesforce
 * on Laravel 4 applications through SOAP
 * Data Integration.
 *
 * @package Davispeixoto\LaravelSalesforce
 */
class Salesforce
{

    /**
     * @var \Davispeixoto\ForceDotComToolkitForPhp\SforceEnterpriseClient sfh The Salesforce Handler
     */
    public $sfh;

    /**
     * The constructor.
     *
     * Authenticates into Salesforce according to
     * the provided credentials and WSDL file
     *
     * @param Environment $environment
     * @throws SalesforceException
     */
    public function __construct($environment)
    {
        try {
             $this->sfh = new Client();

            $wsdl =  getenv('salesforce.'.$environment.'.wsdl');

            $this->sfh->createConnection($wsdl);
                
            $this->sfh->login( getenv('salesforce.'.$environment.'.username'),  getenv('salesforce.'.$environment.'.password') .  getenv('salesforce.'.$environment.'.token'));
            
        } catch (Exception $e) {
            throw new SalesforceException('Exception at Constructor' . $e->getMessage() . "\n\n" . $e->getTraceAsString());
        }
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->sfh, $method), $args);
    }

    /*
     * Debugging functions
     */

    /**
     * @return mixed
     */
    public function dump()
    {
        return print_r($this, true);
    }
}
