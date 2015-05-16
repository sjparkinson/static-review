Feature: Help Option

    As a user
    I want to see a list of avaliable arguments and options
    So that I can work out how to use the application

    Scenario Outline: I run the application with the help option
        When I call the application with "<option>"
        Then I should see:
            """
            Usage:
             static-review [-c|--config="..."] [--adapter="..."] [--review="..."] [path]

            Arguments:
             path                  Spefify the folder to review (default: ".")

            Options:
             --config (-c)         Specify a configuration file to use
             --adapter             Specify the adapter to use
             --review              Specify the reviews to use (multiple values allowed)
             --help (-h)           Display this help message
             --quiet (-q)          Do not output any message
             --verbose (-v|vv|vvv) Increase the verbosity of the output
             --version (-V)        Display the application version
             --ansi                Force ANSI output
             --no-ansi             Disable ANSI output
             --no-interaction (-n) Do not ask any interactive question
            """
        And the application should exit successfully

        Examples:
            | option |
            | -h     |
            | --help |
