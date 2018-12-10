<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->bearerToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjhmMjA0YWE1M2VlMmY2MmI4OWZkZjViOWMxMTg5MmUzNmYxYTM4ZDg1ZGY4OWQ5MTY4MWViMDVkNGNjZGY5NjllNTg1MzI4MjQwMjBjYTljIn0.eyJhdWQiOiI2IiwianRpIjoiOGYyMDRhYTUzZWUyZjYyYjg5ZmRmNWI5YzExODkyZTM2ZjFhMzhkODVkZjg5ZDkxNjgxZWIwNWQ0Y2NkZjk2OWU1ODUzMjgyNDAyMGNhOWMiLCJpYXQiOjE1NDQ0NTM3NjYsIm5iZiI6MTU0NDQ1Mzc2NiwiZXhwIjoxNTc1OTg5NzY1LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.gqOpJBdeWyTSipYUj5SGERrq6n1-xIIE1oAqhG5ExDLQytRH5jBC3toPgtrtPLTo1FfQqrrf3ZYIepWCUp4X52cP0inywLokzI6ikg8NHNlVvyNrv4uYGwXJXacfRMcMNhwXYzQ-BxvponMzGaLpGUYdjhDZo5bGjKvq8LXMySQCqWlO2aa4tnXSOUiFI8ZRyhx5vOs3H9484ZjPoA4MS6pzEWNmW2fkGu-9RAZ40EwmzLHx1nLkQQudBskw7OEbMH6QRovMTXjCNfyf4cnYmHxnKvWFhT1XA-R_-g2wW5WQ-MI8JWC-5tUkmqrLKn7J6NLhsioVJCsLFxYyyvCay4bU15EzoqSQFOO-c5Opn0VvtgLGAG_gFzFVKIqKlMgeDaEtwH5KA4ZK8H-ApbYN8n1UkF1WRFZog30uzjGvTxBJn_3jghoGaBg5ofX5TZdZPk85cxDAJf8uY1pxJXHPG0lTX8smr6s3IAFj1Csfr6qaYU0BdjFuXTK-T5V4WAYlGjsfDqTHBFoV_vtYXKeKJeSVHf6FUTkgBWLLcYBT5pvWTpgsJzl7zYTLt6VhLWUuiw-uB3aX690yQkVGiFqVmm1-qj9WC26XeSKniD5jRLpusEx0c-PPtPjurXWmbPXtc56sDDUrRkx1Dd74zxa_EGdS2Zu6EdGm3xtQx1ZWvWc";
    }

    /**
     * @Given I have the payload:
     */
    public function iHaveThePayload(PyStringNode $string)
    {
        $this->payload = $string;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE|PATCH) ([^"]*)"$/
     */
    public function iRequest($httpMethod, $argument1)
    {
        $client = new GuzzleHttp\Client();
        $this->response = $client->request(
            $httpMethod,
            'http://127.0.0.1:8000' . $argument1,
            [
                'body' => $this->payload,
                'headers' => [
                    "Authorization" => "Bearer {$this->bearerToken}",
                    "Content-Type" => "application/json",
                ],
            ]
        );
        $this->responseBody = $this->response->getBody(true);
    }

    /**
     * @Then /^I get a response$/
     */
    public function iGetAResponse()
    {
        if (empty($this->responseBody)) {
            throw new Exception('Did not get a response from the API');
        }
    }

    /**
     * @Given /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->responseBody);

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->responseBody);
        }
    }

    /**
     * @Then the response contains :arg1 records
     */
    public function theResponseContainsRecords($arg1)
    {
       $data=json_decode($this->responseBody);
       $count = $count($data);
       return ($count == $arg1);

    }


}