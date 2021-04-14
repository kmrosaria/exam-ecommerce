# Install LEMP Stack on Ubuntu 20.04

1. Install Nginx Web Server
 - run `sudo apt update`
 - then install `sudo apt install nginx`
 - check ufw app list `sudo ufw app list`
 - allow Nginx HTTP `sudo ufw allow 'Nginx HTTP'`
 - check ufw status if **Nginx HTTP** is active if return is inactive try running `sudo ufw enable` then check status again
 - goto browser type **localhost** or your **IP address**

2. Install MySQL Database
 - run `sudo apt install mysql-server`
 - then `sudo mysql_secure_installation`
 - after the installation run `sudo mysql` to check if already working
 - create your database `CREATE DATABASE <db_name>;`
 - create a user `CREATE USER '<username>'@'%' IDENTIFIED WITH mysql_native_password BY '<passwd>';`
 - grant db access to that user `GRANT ALL ON <db_name>.* TO '<username>'@'%';`


3. Install PHP
 - run 'sudo apt install php-fpm php-mysql'
 - install required packages 'sudo apt install php-mbstring php-xml php-bcmath'

4. Setting up your local
 - goto /var/www 'cd /var/www'
 - clone this repo `https://github.com/kmrosaria/exam-ecommerce.git`
 - create director for your repo 'sudo mkdir /var/www/<ur_domain>'
 - assign ownership of the directory with the $USER environment variable 'sudo chown -R $USER:$USER /var/www/<ur_domain>'
 - give web server access to `storage` and `cache` 
   - `sudo chown -R www-data.www-data /var/www/<repo>/storage`
   - `sudo chown -R www-data.www-data /var/www/<repo>/bootstrap/cache`

 - create a new config file on Nginx's sites-available directory `sudo nano /etc/nginx/sites-available/<ur_domain>`
    ```
    server {
        listen 80;
        server_name server_domain_or_IP;
        root /var/www/<repo>/public;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-XSS-Protection "1; mode=block";
        add_header X-Content-Type-Options "nosniff";

        index index.html index.htm index.php;

        charset utf-8;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        error_page 404 /index.php;

        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
    ```
 - link your configuration to nginx's sites-enabled directory `sudo ln -s /etc/nginx/sites-available/<ur_domain>/etc/nginx/sites-enabled/`
 - then unlink the default config file from nginx's site-enabled `sudo unlink /etc/nginx/sites-enabled/default`
 - then test your configuration `sudo nginx -t`
 - then reload nginx sudo systemctl reload nginx
 - then open /etc/hosts via vi/vim/nano and add `127.0.0.1   <ur_domain>`
 - visit http://<ur_domain> on your browser


If you want a more detailed tutorial. Go to this [link!](https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-laravel-with-nginx-on-ubuntu-20-04)

### For APIs
 - GET Method
 	- `/api/products/` Get all products.
 	- `/api/products/{product_id}` Get product details
 	- `/api/orders/all` Get all orders
 	- `/api/orders/{user_id}` Get all user orders
 	- `/api/cart/{user_id}` Get user's current cart
 - POST Method
 	- `/api/cart/add`
 		- Payload
 			```
 				{
		            uuid: {product_id},
		            userid: {user_id},
		            quantity: {quantity}
		        }
 			```
 	- `/api/cart/update`
 		- Payload
 			```
 				{
		            uuid: {product_id},
		            userid: {user_id},
		            quantity: {quantity}
		        }
 			```
 	- `/api/cart/remove`
 		- Payload
 			```
 				{
		            uuid: {product_id},
		            userid: {user_id},
		        }
 			```