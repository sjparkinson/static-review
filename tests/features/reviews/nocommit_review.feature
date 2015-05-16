Feature: No Commit Review

    As a user
    I would like to be notified when I have the string "nocommit" in my code
    So that I don't commit it

    Scenario: I run the application with the progress formatter
        Given the configuration file contains:
            """
            adapter: MainThread\StaticReview\Adapter\FilesystemAdapter
            reviews:
                - MainThread\StaticReview\Review\NoCommitReview
            formatter: MainThread\StaticReview\Formatter\NullFormatter
            """
        And the file "test.txt" contains:
            """
            I don't want to commit this. #nocommit
            """
        When I call the application with "test.txt"
        Then I should see:
            """
            @todo
            """
        And the application should not exit successfully
