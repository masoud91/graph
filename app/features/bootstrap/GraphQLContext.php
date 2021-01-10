<?php

use App\DataFixtures\AppFixtures;
use Behat\Gherkin\Node\PyStringNode;
use Behatch\Context\RestContext;
use Behatch\HttpCall\Request;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use GraphQL\Type\Introspection;
use Coduo\PHPMatcher\Matcher;
use Coduo\PHPMatcher\Factory\MatcherFactory;
use Predis\Client;


/**
 * Context for GraphQL.
 *
 */
final class GraphQLContext extends RestContext
{
    /**
     * @var AppFixtures
     */
    private $fixtures;

    /** @var EntityManagerInterface */
    private $em;

    /** @var Matcher */
    private $matcher;

    /** @var RestContext */
    private $restContext;

    /** @var array */
    private $graphqlRequest;

    /** @var int */
    private $graphqlLine;

    /** @var Client */
    private $redis;

    /** @var Behatch\HttpCall\Request\Goutte $request */
    protected $request;

    /**
     * FeatureContext constructor.
     * @param Request $request
     * @param AppFixtures $fixtures
     * @param EntityManagerInterface $em
     * @param Client $redis
     */
    public function __construct(
        Request $request,
        AppFixtures $fixtures,
        EntityManagerInterface $em,
        Client $redis
    )
    {
        parent::__construct($request);

        $this->request = $request;
        $this->fixtures = $fixtures;
        $this->em = $em;
        $this->matcher = (new MatcherFactory())->createMatcher();
        $this->redis = $redis;
    }

    /**
     * @BeforeScenario
     * @createSchema
     *
     * @throws ToolsException
     */
    public function createSchema() : void
    {

        var_dump("create schema called");

        // Get entity metadata
        $classes = $this->em->getMetadataFactory()->getAllMetadata();

        // Drop and create schema
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);
    }

    /**
//     * @BeforeScenario
     * @loadFixtures
     */
    public function loadFixtures() : void
    {
        var_dump("load fixtures called");

        $this->redis->flushall();

        // Load fixtures and Execute
        $purger = new ORMPurger($this->em);
        $fixtureExecutor = new ORMExecutor($this->em, $purger);
        $fixtureExecutor->execute([]);
//        $fixtureExecutor->execute([$this->fixtures]);
    }

    /**
     * @Given I send a GraphQL request as follow:
     * @param PyStringNode $json
     */
    public function iSendAGraphQLRequestAsFollow(PyStringNode $json) : void
    {
        $this->request->setHttpHeader('Content-Type', 'application/json');

        $requestBody = json_encode( [ 'query' => $json->getRaw() ]);

        $this->request->send(
            'POST',
            $this->locatePath('api/graphql'),
            [],
            [],
            $requestBody
        );
    }

    /**
     * @Then the JSON match expected template:
     * @param PyStringNode $json
     */
    public function theJsonMatchExpectedTemplate(PyStringNode $json) : void
    {
        $actual = $this->request->getContent();

        $this->assertTrue(
            $this->matcher->match($actual, $json->getRaw())
        );
    }

    /**
     * @When I send the GraphQL request with variables:
     * @param PyStringNode $variables
     */
    public function ISendTheGraphqlRequestWithVariables(PyStringNode $variables)
    {
        $this->graphqlRequest['variables'] = $variables->getRaw();
        $this->sendGraphqlRequest();
    }

    /**
     * @When I send the GraphQL request with operationName :operationName
     * @param string $operationName
     */
    public function ISendTheGraphqlRequestWithOperation(string $operationName)
    {
        $this->graphqlRequest['operationName'] = $operationName;
        $this->sendGraphqlRequest();
    }

    /**
     * @When I send the query to introspect the schema
     */
    public function ISendTheQueryToIntrospectTheSchema()
    {
        $this->graphqlRequest = ['query' => Introspection::getIntrospectionQuery()];
        $this->sendGraphqlRequest();
    }

    private function sendGraphqlRequest()
    {
        $this->request->send('POST', $this->locatePath('/api/graphql'), [], [], json_encode($this->graphqlRequest));
    }
}