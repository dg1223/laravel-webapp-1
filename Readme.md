# Issues with Laravel setup on Ubuntu

1. ### Homestead.yaml

    Check if folders>map has the full path to your laravel project.

2. ### Package installation

    Disable IPv6 on Ubuntu. Otherwise, you'll get connection timeout error when using 'composer require' to install a package.

    To disable IPv6:
    * Open /etc/sysctl.conf
    * Add the following line: net.ipv6.conf.all.disable_ipv6 = 1
    * Save the file

3. ### .env

    Update this file in your laravel application with the project's database name as DB_DATABASE, username as DB_USERNAME and password as DB_PASSWORD.

    Yes, password is exposed in this file and I don't like it even if you say it'll always be on the server.

    You cannot update your user/password by just changing it here. You have to do it through your database and then update the credentials here to match    that of the database.
