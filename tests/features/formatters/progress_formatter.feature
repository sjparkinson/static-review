Feature: Progress Formatter

    As a user
    I would like to specify the format of the applications output
    So that I can use the most useful output format

    Scenario: I run the application with the progress formatter
        Given the configuration file contains:
            """
            adapter: MainThread\StaticReview\Adapter\FilesystemAdapter
            reviews:
                - MainThread\StaticReview\Review\NoCommitReview
            formatter: MainThread\StaticReview\Formatter\ProgressFormatter
            """
        And the file "test.txt" contains:
            """
            Testing file.
            """
        When I call the application with "test.txt"
        Then I should see:
            """
            Reviewing file 1 of 1.
            1 reviews (1 passed, 0 failed)
            """
        And the application should exit successfully
