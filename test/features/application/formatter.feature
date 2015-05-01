Feature: Output Formatters

    As a user
    I would like to specify the format of the applications output
    So that I can use the most useful output format

    Supported formatters:
        - progress

    Scenario: I run the application with the progress formatter
        Given the configuration file contains:
            """
            driver: MainThread\StaticReview\Driver\LocalDriver
            reviews: null
            formatter: MainThred\StaticReview\Output\Formatter\ProgressFormatter
            """
        And the file "test.txt" contains:
            """
            Testing file.
            """
        When I run the application
        Then I should see:
            """
            .
            """
        And the application should exit successfully
