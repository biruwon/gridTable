### Installation instruction

- Clone the repository:

	    git clone https://github.com/biruwon/gridTable.git

- Installing dependencies using composer(http://getcomposer.org/)

	    php composer.phar install

  And configure the database, user permissions and virtualhost(if needed)

- Deploy the application using the deploy script in bin/deploy.sh or run the following commands:

        php app/console d:d:d --force
        php app/console d:d:c
        php app/console d:s:u --force
        php app/console d:f:l

- Go to index page:

	    http://yourvirtualhost/app_dev.php/
