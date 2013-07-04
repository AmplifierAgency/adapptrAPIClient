<?php


namespace AdapptrAPI;

class AppComponent extends \CApplicationComponent
{
    /**
     * @var string the file that contains the service description
     */
    public $descriptionFile;

    /**
     * @var \Guzzle\Service\Description\ServiceDescription the service description for the api
     */
    protected $_description;

    /**
     * @var \Guzzle\Service\Client
     */
    protected $_client;

    /**
     * Initialize the app component
     */
    public function __construct()
    {
        $this->descriptionFile = __DIR__.'/adapptrAPIv2.json';
    }

    /**
     * @param \Guzzle\Service\Description\ServiceDescription $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return \Guzzle\Service\Description\ServiceDescription
     */
    public function getDescription()
    {
        if ($this->_description === null)
            $this->_description = \Guzzle\Service\Description\ServiceDescription::factory($this->descriptionFile);
        return $this->_description;
    }

    /**
     * @param \Guzzle\Service\Client $client
     */
    public function setClient($client)
    {
        $this->_client = $client;
    }

    /**
     * @return \Guzzle\Service\Client
     */
    public function getClient()
    {
        if ($this->_client === null) {
            $this->_client = new \Guzzle\Service\Client;
            $this->_client->setDescription($this->getDescription());
        }
        return $this->_client;
    }

    /**
     * Gets the named command from the api service and executes it
     * PHP Magic Method, do not call directly.
     *
     *
     * @param string $name the name of the command
     * @param array $parameters the parameters to pass to the command, only the first is used and should be an array.
     *
     * @return mixed the service response
     */
    public function __call($name, $parameters)
    {
        $parameters = array_shift($parameters);
        if (empty($parameters))
            $parameters = array();
        $client = $this->getClient();
        $command = $client->getCommand($name, $parameters);
        return $client->execute($command);
    }
}
