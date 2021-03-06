# Camagru
Camagru is a small, Instagram-like website that allows users to take and share photo edits, written primarily in PHP.
This project was an introduction to the fundamentals of web development, PHP, and other basic web development technologies.

## Requirements
- PHP
- XAMPP
- JavaScript

## Instalation and Setup
NOTE: This is for setting the project to run on Arch Linux Manjaro using Xampp. Follow your OS specific instructions for other platforms.
- Install Xampp:
    ```
    sudo pacman -S xampp
    ```
- Set up a database user and initialize the database:
    [Mysql Arch Wiki](https://wiki.archlinux.org/index.php/PHP#MySQL/MariaDB)
- Clone the project into the /opt/lamp/htdocs directory
    ```
    git clone https://github.com/tcajee/camagru.git /opt/lamp/htdocs/camagru
    ```
- Start the Xampp servers via the included GUI.
- Initialize the Database tables by calling the setup script [Database Setup](https://localhost/camagru/config/setup.php)
- Navigate to [PHP Myadmin](https://localhost/phpmyadmin/) to verify that the database has been populated.
- After running the setup you will be redirected to [Camagru Home](https://localhost/camagru/)

## Tools & Technologies
#### Back-end:
- Apache:  An open source web server and the most widely used server worldwide for delivery of web content. Used to  serves the project on localhost for development.
- PHP: A general-purpose language suited for web development (requirement of the project).
#### Front-end:
- HTML: The standard markup language used to develop web Pages. It only defines the layout of the page contents. 
- CSS: Used to define the style of the website. It augments HTML and can be used to create semi-dynamic websites.
- JavaScript: A programming language that is often used alongside HTML and CSS to make dynamic websites. It is usually referred to as the language of the web because of its strong compatibility with web browsers and HTML.
#### Data Management System (DBMS):
- MariaDB: An open source SQL server used as the primary database driver along with the Structured Query Language we used for the project database queries.

#### Project structure:
```
camagru
├── app
│   ├── controllers -> Contains all page-specific back-end functionality
│   │   └── *.php
│   ├── lib -> Contains addition helper functionality
│   │   └── helpers
│   │       └── *.php
│   └── views -> Contains templates and all page-specific layouts
│       ├── *.php
│       ├── layouts
│       │   └── *.php
│       └── *.php
├── config -> Contains all initial application and database functionality
│   └── *.php
├── core -> Contains all primary back-end application functionality
│   └── *.php
├── css -> Contains all stylesheets
│   └── *.css
├── img -> Contains all image files
│   └── *.png
│   └── *.jpg
├── index.php -> Main entry point to the application
└── js -> Contains all page-specific client-side functionality and AJAX
   └── *.js
```

## Testing
[https://github.com/tcajee/camagru/blob/master/camagru.pdf](https://github.com/tcajee/camagru/blob/master/camagru.pdf)

* The following are tests I ran:<br/>
 1.Preliminary checks<br/>
 **Usage of PHP.<br/>
    b.No external frameworks.<br/>
  c.Config/database.php<br/>
  d.Config/setup.php<br/>
  e.PDOs<br/>
