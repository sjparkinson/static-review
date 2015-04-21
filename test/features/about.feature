Feature: About Command

    Scenario: I run the about command
        When I run "about" with "-vvv"
        Then I should see
            """
            """
