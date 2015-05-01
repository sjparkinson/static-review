Feature: Progress Formatter

    As a user
    I would like to specify the format of the applications output
    So that I can use the most useful output format

    Scenario: I run the application with the progress formatter
        Given the configuration file contains:
            """
            driver: MainThread\StaticReview\Driver\LocalDriver
            reviews:
                - MainThread\StaticReview\Review\VarDumperReview
            formatter: MainThread\StaticReview\Formatter\ProgressFormatter
            """
        And the file "test.txt" contains:
            """
            Testing file.
            """
        When I run the application
        Then I should see:
            """
            ..
            """
        And the application should exit successfully
