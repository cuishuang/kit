    server {
        listen       65501;
        server_name  db.mylabclub.com;

        access_log  /var/log/db_pg_demo.access.log  main;
        error_log  /var/log/db_pg_demo.error.log;
        root /Users/shuangcui/sql_doc/db_pg_demo.php;

        location / { rewrite ^$      /public/ last;
             rewrite ^(.*)$  /public/index.php?_url=$1 last;
         }

         location ~ \.php$ {
             fastcgi_split_path_info     (.*\.php)(.*)$;
             fastcgi_pass                127.0.0.1:9000;

             fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
             fastcgi_param   PATH_INFO       $fastcgi_path_info;
             include fastcgi_params;
         }



         error_page   500 502 503 504  /50x.html;
         location = /50x.html {
             root   html;
         }

     }
