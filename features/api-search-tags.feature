@api
Feature: Check api route api-search-tags
  Scenario: It receives a response from search tag api endpoint
    When I request "/api/tag/search" using HTTP "POST"
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
