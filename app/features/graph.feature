Feature: graphql CRUD for graphs
  @createSchema
  Scenario: create a sample graph
    Given I send a GraphQL request as follow:
    """
    mutation {

      ##
      # create a node_type for "city"
      ##
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

      ##
      # create "Berlin" node of type "city"
      ##
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

      ##
      # create a node_type for "country"
      ##
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

      ##
      # create "Germany" node of type "country"
      ##
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

      ##
      # create a edge_type for "is in"
      ##
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

      ##
      # create a relation between "Berlin" with "germany" using "is in" edge
      # [Berlin] ---- is in ----> [Germany]
      ##
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
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"