@api
Feature: Check api route api-search-user-by-email
  Scenario: It receives a response from search user by email api endpoint
    When I request "/api/user/search-by-email" using HTTP "POST"
    Then the response code is 200
    Then the response body contains JSON:
      """
      {
          "items": "@variableType(array)"
      }
      """
    Then the response body contains JSON:
      """
      {
          "items": [
              {
                  "id": "@variableType(int)",
                  "text": "@variableType(string)"
              }
          ]
      }
      """
