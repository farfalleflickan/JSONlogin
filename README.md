# JSONlogin
Minimal HTML login page that uses a json file as a database


Minimal login system that requires a new user to input username, password and then give the generated string (which contains the username and the encrypted password) to the webadmin. The webadmin then has to add the entry in the "database" file, which is called ".user_db", in the same folder as the rest of the files.
OBVIOUSLY, fairly useless unless you have HTTPS, fairly useless if the user passes the password to the webadmin through an insecure connection...

To prevent user from accessing a page, put this text on top of the file:
```php
<?php
session_start();
if (isset($_SESSION['login_user']) == false || empty($_SESSION['login_user'])) {
    header("Location:login.html");
}
?>
```
and rename your file to ```.php```

If you want to protect more than just a web page, you should have something similar to this (done in Nginx):
```
location ^~ /yourlocation {
            index index.php;
            set $tmp 0;

            if ($http_cookie !~* "session_key_active"){
                set $tmp 1;
            }
            if ( $request_filename ~ "login.*"){
                set $tmp 0;
            }

            if ($tmp = 1){
                rewrite ^/.* https://dariorostirolla.se/yourlocation/login.html last;
            }

            location ~ \.php$ {
                fastcgi_pass unix:/run/php-fpm/php-fpm.sock;
                fastcgi_index index.php;
                include fastcgi.conf;
                fastcgi_param SCRIPT_FILENAME $request_filename;
                try_files $uri =404;
            }
        }

```


WIP, more clear instructions & coming... sometime in the future :)
