# OTRS address extractor

A command line tool to extract addresses from an OTRS CSV export.

Source file must be an OTRS export with email bodies, ticket numbers and email addresses.

Usage:

    php extract_address.php --output output.csv exported_tickets.csv