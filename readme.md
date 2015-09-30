Lowdown
===
A weekly lowdown of all UCC Society events based on facebook events from the societies.

![](http://images.netsoc.co/lowdown-logo.jpg)

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
APP_ENV=production
APP_DEBUG=false
APP_KEY={32$9;`GTky*=A"qw&v+-pe,?rGz$+/E
APP_URL=http://localhost

DB_HOST=localhost
DB_DATABASE=lowdown
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=database

MAIL_DRIVER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=username
MAIL_PASSWORD=password
MAIL_ENCRYPTION=null
MAIL_KEY=aaaabbbb11111
MAIL_ADDRESS=lowdown@netsoc.co
MAIL_NAME=Lowdown

DEV_EMAIL=netsoc@uccsocieties.ie
SITE_TITLE=Lowdown
BASE_URL=http://lowdown.dev

FB_ID=123456789
FB_SECRET=aaaabbbb11111
```

#### Laravel Migration

Before running the following commands, be sure to replace the societies in [DatabaseSeeder.php](https://github.com/UCCNetworkingSociety/lowdown/blob/master/database/seeds/DatabaseSeeder.php). (Currently working on a .csv solution to this)

```bash
# Run database migrations (table creation)
php artisan migrate

# Fetch initial data (the first society events)
php artisan db:seed
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