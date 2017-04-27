# StreamFlow

A web dashboard for visualising passenger flows, providing insight into transport disruptions' effects and possible mitigations.

## About

The project is currently configured for a demonstration, showing only data for a single day, in Milan. It should be
fairly easy to modify it in order to account for new data. Please find instructions to do that below.

## Installation

In order to get a working version of this program, first clone the repository. Make sure you have Python 2.7 and
Laravel 5.4 with all its requirements.
Below, **/** is be the root of the repo.
1. In **/www**, set up the Laravel .env file (copy .env.example and add your info)
2. Set up a symlink from your web server root (e.g. **/var/www/streamflow**) to **/www/public**
3. In **/www**, finish setting up Laravel by running `php artisan key:generate`, `composer update` and `php artisan publish:vendor`
4. Set up the databse, running `php artisan migrate` in **/www**
5. If everything went well, you should see the dashboard when going to **localhost/streamflow**

### Problems?

If you have any problems, just look up on how to set up a Laravel project, and follow those steps.
Most likely there was a problem of Laravel, not specific to this project.

### Change source for data




