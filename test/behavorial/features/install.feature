Feature: Install

  As a user
  I want interactivly install a static-review hook in my project
  So that I don't have to fiddle about with configuration
  
  Backgroud:
    Given I have installed static-review

  Scenario: Install static-review in a project
    Given I am in a "PHP" project versioned with "git"
    When I enter "static-review install"
    Then I should see:
        """
        What hook would you like to install [pre-commit]: 
        """
    
    When I press enter
    Then I should see:
        """
        Would you like to define your reviews interactively [yes]? 
        """
    
    When I press enter
    Then I should see a list of installed reviews
    And I should see:
        """
        Include a review []: 
        """
    
    When I enter "MainThread\StaticReview\Review\NoCommitReview"
    Then I should see:
        """
        Include a review []: 
        """
    
    When I press enter
    Then I should see:
        """
        Do you confirm generation [yes]? 
        """
    
    When I press enter
    Then a file should exist at ".git/hooks/pre-commit"