Lowdown
===
A weekly lowdown of all UCC Society events based on facebook events from the societies.

## Table Of Contents
* [Setup](#setup)
    * [Vagrant Box](#vagrant-box)
    * [Add an entry to your hosts file](#add-an-entry-to-your-hosts-file)
    * [.env file](#env-file)
    * [Node and Gulp](#node-and-gulp)
    * [You're good to go!](#youre-good-to-go)

## Setup

### Vagrant Box

```bash
# Adds the box permanently to our collection as well as downloads it
vagrant box add Netsoc https://dl.dropboxusercontent.com/u/20999985/Vagrant%20Boxes/Netsoc.box

# Launches the Virtual Machine
vagrant up
```

### Add an entry to your hosts file

* On Mac and Linux, you can open the terminal and type `sudo vim /etc/hosts`.
* On Windows, you'll have to open notepad in administrator mode and then go to `C:\\Windows\System32\drivers\etc\hosts.txt`

Add the following to the end:

```
172.22.22.25 lowdown.dev
```

### .env file

You'll need to create a `.env` file in the root directory of your application (i.e. where your Vagrantfile is). Below is an _example_ `.env` file you can use.

```php
APP_ENV=local
APP_DEBUG=true
APP_KEY={32$9;`GTky*=A"qw&v+-pe,?rGz$+/E
APP_URL=http://localhost

DB_HOST=localhost
DB_DATABASE=lowdown
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

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

FB_ID=123456789
FB_SECRET=aaaabbbb11111
```

### Node and Gulp
Gulp is a build system which just means it's sort of like a customisable compiler for anything you're doing. We're using it to 1) compile LESS files and 2) refresh the browser whenever we make a change to a view or css.

In order to use Gulp, you will have to [install Node.js](https://nodejs.org/download/).

When Node is installed, simply navigate to your code's root directory (i.e. where the [package.json file](https://github.com/UCCNetworkingSociety/lowdown/blob/master/package.json) is) and run the following command:

```bash
npm install
```

Everything you'll need will be installed in the node_modules folder. So to start the project, run the following:

```bash
gulp watch
```

### You're good to go! 
Now you can go to [http://localhost:3000/public](http://localhost:3000/public) to access the project and have it reload any time you make a change to the styling or layout.