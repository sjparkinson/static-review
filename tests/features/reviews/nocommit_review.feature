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
            """
        And the file "test.txt" contains:
            """
            I don't want to commit this. #nocommit
            """
        When I call the application with "test.txt"
        Then I should see:
            """
            Reviewing file 1 of 1.
            AbstractReview: A "nocommit" string was found in test.txt
            1 reviews (0 passed, 1 failed)
            """
        And the application should not exit successfully
