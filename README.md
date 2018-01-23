# OTRS address tool

A command line tool to 

* extract addresses from an OTRS CSV export or the OTRS database
* update the tickets in OTRS with a new owner to mark them as "lower priority"


## Configuration

Create a file called `.env` in the directory where you run the script. It must contain the database access credentials 
and the OTRS API credentials. 

The username and password for the OTRS API only need to be a valid user, *not* necessarily 
the user you want to assign the tickets to.

You can use the provided `env.example` as a template.
   
## Extract addresses from OTRS database

Run the command

    php otrs_tool.php extract:db --output output.csv --rejected rejected.csv

If you want to output the ticket IDs with direct links to your OTRS instance, run the command like this:

    php otrs_tool.php extract:db --link-template="https://example.com/otrs/index.pl?Action=AgentTicketZoom;TicketID=%d" --output output.csv --rejected rejected.csv

## Update tickets in OTRS

You can update the tickets with a new user, e.g. "address-check" to mark them as "someone has already looked at this". 

In OTRS, find out the user id of the user you want to assign the tickets to. 
In the following example that user ID is 27.

Run the command

    php otrs_tool.php update-ticket -owner 27 change_tickets.csv

Running the command can take some time for many tickets.

## Running the tests

    composer test