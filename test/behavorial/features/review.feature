Feature: Review

  As a user
  I want to review files for issues
  So that I am made aware of common mistakes

  Scenario: Review a file with no issues
    Given I have a PHP file with no issues
    When I review my files for mistakes
    Then I should see:
      """
      Good job, no mistakes!
      """

  Scenario: Review a file with issues
    Given I have a PHP file with issues
    When I review my files for mistakes
    Then I should see:
      """
      Oh no...
      """