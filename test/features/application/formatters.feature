Feature: Output Formatters

    As a user
    I would like to specify the format of the applications output
    So that I can use the most useful output format

    Supported formatters:
        - progress

    Scenario: I run the application with the progress formatter
        Given the configuration file contains:
            """
            driver: file
            reviews: null
            format: progress
            """
        And the file "test.txt" contains:
            """
            Testing file.
            """
        When I run the application
        Then I should see:
            """
            -

            0 reviews
            1 file (1 skipped)
            """
        And the application should exit successfully
