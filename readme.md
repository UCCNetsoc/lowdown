Lowdown
===
A weekly lowdown of all UCC Society events based on facebook events from the societies.

[![](http://images.netsoc.co/lowdown-logo.jpg)](http://lowdown.netsoc.co)

## Requirements
* Apache
* PHP 5.6
* MySQL
* [Composer (PHP)](https://getcomposer.org/doc/00-intro.md#globally)
* [Gulp (NPM)](https://www.npmjs.com/package/gulp-install)

## Installation
### Git
```bash
cd /var/www/html/lowdown

# Clones the git repo into your current directory (must be empty)
git clone https://github.com/UCCNetworkingSociety/lowdown.git .
```

### Composer/Laravel Setup

```bash
# Installs dependencies and generates components
composer update
```

#### .env file
Place a .env file in the root of your application using the following as a template.

```bash
# APP_ENV={local|production|testing}
APP_ENV=production

# Enable debug messages?
APP_DEBUG=false

# Unique app_key (mash the keyboard for a while)
APP_KEY={32$9;`GTky*=A"qw&v+-pe,?rGz$+/E

# Required by laravel, effectively the same as BASE_URL
APP_URL=http://localhost

# Database details
DB_HOST=localhost
DB_DATABASE=lowdown
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=database

# Enables the route "/events/update" to kick off the queue for pulling in events
ENABLE_UPDATE_QUEUE_KICKOFF=true

# Mail settings
MAIL_DRIVER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=username
MAIL_PASSWORD=password
MAIL_ENCRYPTION=null
MAIL_KEY=aaaabbbb11111
MAIL_ADDRESS=lowdown@netsoc.co
MAIL_NAME=Lowdown

# Developer Email
DEV_EMAIL=netsoc@uccsocieties.ie

# Title of the project
SITE_TITLE=Lowdown

# URL For your issue tracker
ISSUE_TRACKER=https://github.com/UCCNetworkingSociety/lowdown/issues

# Domain/subdomain name (EG: lowdown.netsoc.co)
DOMAIN_NAME=lowdown.netsoc.co
BASE_URL=http://lowdown.dev

# The ID and Secret of a facebook app you created to access the API
FB_ID=123456789
FB_SECRET=aaaabbbb11111

# Used if you want to use BugSnag to track bugs
BUGSNAG_API_KEY=aaaabbbb11111
```

#### Laravel Migration

Before running the following commands, be sure to change the list of societies in (lost_of_societies.csv)[https://github.com/UCCNetworkingSociety/lowdown/blob/master/list_of_societies.csv]. They're done in the form:

```
Society Name,facebook_reference

For Example:
Networking Gaming and Technology,UCCNetsoc
```

```bash
# Run database migrations (table creation)
php artisan migrate

# Fetch initial data (the first society events)
php artisan db:seed

# Create queue table
php artisan queue:table
```

### Node, Gulp and LESS
To use the application, we have to compile the less for styling but to do that, we'll need to install some dependencies from node.

```bash
cd /var/www/html/lowdown

# Install node dependencies
npm install

# Run gulp (assuming it's installed), it should take care of the rest
gulp
```

### Job Queues
Our application relies on using a job queue to continually fetch and refresh society event information. This job queue is initialised by the command `php /var/www/html/lowdown/artisan queue:work` if you want to implement your own method for indefinitely running.

#### Supervisor
To manage the queue process, we're using Supervisor. 

```bash
# Install on ubuntu
sudo apt-get install supervisor
```

All of the supervisor processes are stored in `/etc/supervisor/etc.d`. We'll add the following file `lowdown-queue.conf` to that directory.

```
[program:lowdown-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/lowdown.netsoc.co/artisan queue:work
autostart=true
autorestart=true
user=netsoc
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/html/lowdown.netsoc.co/worker.log
```

To add and run it, we'll use the below:

```bash
# Add our conf to supervisor
sudo supervisorctl reread

# Have supervisor prepare for our processes
sudo supervisorctl update

# Start the worker
sudo supervisorctl start lowdown-queue:*
```