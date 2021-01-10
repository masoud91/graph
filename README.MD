# Graph

### Installation
To run the project, all you need is a unix OS with docker and docker-compose installed.

The project can easily up and run using the following commands:  
```bash
docker build .
docker-compose run --rm app sh -c "composer install"
mv app/.env.local.dist app/.env.local; mv app/.env.test.local.dist app/.env.test.local 
```
Then run:
```bash
docker-compose up
```

The docker compose do the rest.   


### Let's Try
Let's open [GraphiQL](http://localhost/api/graphql/graphiql) online IDE for GraphQL and run some queries and mutations.  

First create a NodeType. A NodeType with name "City" which represent City nodes.
```
mutation {
  node_type__city: createGNodeType(input: {
    name: "city",
    clientMutationId: "1"
  }) {
    clientMutationId
    gNodeType {
      id
      name
    }
  }
}
```
Output:
```
{
  "data": {
    "node_type__city": {
      "clientMutationId": "1",
      "gNodeType": {
        "id": "/api/g_node_types/1",
        "name": "city"
      }
    }
  }
}
```
Then create a Node of Type "city". What about Berlin?
```
mutation {
  node__berlin: createGNode(input: {
    name: "Berlin",
    GNodeType: "/api/g_node_types/1",
    metadata: {
      population: "3.645 million",
      area: "891.8 km²",
      elevation: "34 m"
    },
    clientMutationId: "2"
  }) {
    clientMutationId
    gNode {
      id
      name
    }
  }
}
```
Output:
```
{
  "data": {
    "node__berlin": {
      "clientMutationId": "2",
      "gNode": {
        "id": "/api/g_nodes/1",
        "name": "Berlin"
      }
    }
  }
}
```

And another node type which is "country".
```
mutation {
  node_type__country: createGNodeType(input: {
    name: "country",
    clientMutationId: "3"
  }) {
    clientMutationId
    gNodeType {
      id
      name
    }
  }
}
```
Output:
```
{
  "data": {
    "node_type__country": {
      "clientMutationId": "3",
      "gNodeType": {
        "id": "/api/g_node_types/2",
        "name": "country"
      }
    }
  }
}
```

So, add a "Germany" node of type "country"
```
mutation {
  node__germany: createGNode(input: {
    name: "Germany",
    GNodeType: "/api/g_node_types/2",
    metadata: {
      population: "83.02 million",
      capital: "Berlin",
      dialing_code: "+49"
    },
    clientMutationId: "4"
  }) {
    clientMutationId
    gNode {
      id
      name
    }
  }
}
```
Output:
```
{
  "data": {
    "node__germany": {
      "clientMutationId": "4",
      "gNode": {
        "id": "/api/g_nodes/2",
        "name": "Germany"
      }
    }
  }
}
```

Now we have two nodes. Let's make a relation between these two. First we need an EdgeType.
```
mutation {
  edge_type__is_in: createGEdgeType(input: {
    name: "is_in",
    clientMutationId: "5"
  }) {
    clientMutationId
    gEdgeType {
      id
      name
    }
  }
}
``` 
Output:
```
{
  "data": {
    "edge_type__is_in": {
      "clientMutationId": "5",
      "gEdgeType": {
        "id": "/api/g_edge_types/1",
        "name": "is_in"
      }
    }
  }
}
```

Then we can create an Edge Between Berlin and Germany. Let's name it "is in". like this:

##### [Berlin] ---- is in ----> [Germany]

```
mutation {
  edge__berlin_is_in_germany: createGEdge(input: {
    name: "Berlin is in Germany",
    GEdgeType: "/api/g_edge_types/1"
    fromNode: "/api/g_nodes/1",
    toNode: "/api/g_nodes/2",
    metadata: {
      capital: true
    }
    clientMutationId: "6"
  }) {
    clientMutationId
    gEdge {
      id
      name
      fromNode {
        id
        name
      }
      toNode {
        id
        name
      }
      metadata
    }
  }
}
```
Output:
```
{
  "data": {
    "edge__berlin_is_in_germany": {
      "clientMutationId": "6",
      "gEdge": {
        "id": "/api/g_edges/1",
        "name": "Berlin is in Germany",
        "fromNode": {
          "id": "/api/g_nodes/1",
          "name": "Berlin"
        },
        "toNode": {
          "id": "/api/g_nodes/2",
          "name": "Germany"
        },
        "metadata": {
          "capital": true
        }
      }
    }
  }
}
```
Let's see what we have.
```
{
  gEdges{
    edges{
      node{
        name
        fromNode{
          id
          name
          metadata
        }
        toNode {
          id
          name
          metadata
        }
        metadata
      }
    }
  }
}
```
Output:
```
{
  "data": {
    "gEdges": {
      "edges": [
        {
          "node": {
            "name": "Berlin is in Germany",
            "fromNode": {
              "id": "/api/g_nodes/1",
              "name": "Berlin",
              "metadata": {
                "area": "891.8 km²",
                "elevation": "34 m",
                "population": "3.645 million"
              }
            },
            "toNode": {
              "id": "/api/g_nodes/2",
              "name": "Germany",
              "metadata": {
                "capital": "Berlin",
                "population": "83.02 million",
                "dialing_code": "+49"
              }
            },
            "metadata": {
              "capital": true
            }
          }
        }
      ]
    }
  }
}
```
Wow! That's it. we create a small graph containing two nodes and one directed edge.

Feel free to create as many as nodes and edges you want with any custom type for nodes and edges.  
There is an autogenerated Docs in the top right corner of the page which guide you how to perform a full CRUD 
operation using GraphQL queries and mutations. 

### Extensibility
You can have any Entity which means Node and Edge to you and make them Edge or Node by implementing 
NodeInterface or EdgeInterface and the system would take care of the rest.  
It is not a full featured and complete solution (jsut a MVP) but I implemented User and Follow entities and
make them Node and Edge respectively by implementing the corresponding interfaces.  
You can create User and Follow using their API endpoints and see how system make the associated graph 
automatically.  


### Traverse the graph
Regardless of the algorithm you are using, one of the key data you need to traverse a graph for any kind of problems
like finding the shortest path between two nodes is having the neighbours of a node.   
I provide a REST endpoint (I face a bug to its GraphQL implementation which I will fix later :) )
that give you the neighbours of each node grouped by "in" and "out" neighbours, which is really useful for
any client who wants to traverse the graph. 

check them here:  
http://localhost/api/g_nodes/1/neighbours/out  
http://localhost/api/g_nodes/2/neighbours/in

I ues a redis database and store list of neighbours in to SET data type for each node as:  
`node:[id]:in` and `node":[id]:out`

### Tests
You can run the acceptance test using the following command:
```bash
docker-compose -f docker-compose-test.yml run --rm test sh -c "vendor/bin/behat"
```
these tests are written using Behat and Gherkin but just a few test provided to check the main functionality.  

note: please rerun the command above after a few seconds if you get the following error at the first try:  
`An exception occurred in driver: SQLSTATE[HY000] [2002] Connection refused (Doctrine\DBAL\Exception\ConnectionException)`  
this is a docker health check issue which I need to take care of it.

### Code Quality
I linted my codes based on PSR2 and double-checked it with a fixer library.
Here is the fixer command:
```bash
docker-compose exec app sh -c "vendor/bin/php-cs-fixer fix src/ --rules=@PSR2"
```   

Also, I would take care of almost all the-most-restricted-ever phpStan level7 warnings for ensuring quality.
Using the following command you can check them easily:
(I didn't have enough time to fix all of them)
```bash
docker-compose exec app sh -c "vendor/bin/phpstan analyse src --level 7"
```   
