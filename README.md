# OTRS address extractor

A command line tool to extract addresses from the OTRS database.

Source file must be an OTRS export with email bodies, ticket numbers and email addresses.

## Usage with OTRS database

Create a file called `.env` in the directory where you run the script. It must contain the database access credentials.
You can use the provided `env.example` as a template. Then you can run the command

    php otrs_tool.php extract:db --output output.csv --rejected rejected.csv

If you want to output the ticket IDs with direct links to your OTRS instance, run the command like this:

    php otrs_tool.php extract:db --link-template="https://example.com/otrs/index.pl?Action=AgentTicketZoom;TicketID=%d" --output output.csv --rejected rejected.csv

## Running the tests

    composer test