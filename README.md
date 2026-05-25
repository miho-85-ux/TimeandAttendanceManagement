# 勤怠管理アプリ  

## 環境構築  
### Dockerビルド  
1. `git clone git@github.com:miho-85-ux/TimeandAttendanceManagement.git`  
2. `cd TimeandAttendanceManagement`  
3. DockerDesktopアプリを立ち上げる  
```bash  
docker-compose up -d --build  
```  
4. プロジェクト直下で以下のコマンドを入力
```bash
make init
```
※Makefileを使用しています。実行するコマンドを省略することができます。

5. envに以下の環境変数を追加  
``` text  
DB_CONNECTION=mysql 
DB_HOST=mysql 
DB_PORT=3306 
DB_DATABASE=laravel_db 
DB_USERNAME=laravel_user 
DB_PASSWORD=laravel_pass 
```  

## Mailtrap    
1. Mailtrapのアカウントを作る  
```bash  
https://mailtrap.io  
```
2. SandboxのCredentialsを.envに設定  
```text  
MAIL_MAILER=smtp
MAIL_HOST=[your-smtp-host]
MAIL_PORT=2525
MAIL_USERNAME=[your-username]
MAIL_PASSWORD=[your-password]
MAIL_ENCRYPTION=tls
```
3. キャッシュをクリア
```bash
php artisan config:clear
```
4. メールを確認


## PHPUnitを利用したテストに関して
以下のコマンド:  
```
//テスト用データベースの作成
docker-compose exec mysql bash
mysql -u root -p
//パスワードはrootと入力
create database test_database;

docker-compose exec php bash
php artisan migrate:fresh --env=testing
./vendor/bin/phpunit
```
※.env.testingにもStripeのAPIキーを設定してください。  

## テーブル仕様書について
### usersテーブル
| カラム名 | 型 | primary key | unique key | not null | foreign key |
| --- | --- | --- | --- | --- | --- |
| id | bigint | ◯ |  | ◯ |  |
| name | varchar(255) |  |  | ◯ |  |
| email | varchar(255) |  | ◯ | ◯ |  |
| email_verified_at | timestamp |  |  |  |  |
| password | varchar(255) |  |  | ◯ |  |
| role | varchar(255) |  |  |  |  |
| remember_token | varchar(100) |  |  |  |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |


### attendancesテーブル
| カラム名 | 型 | primary key | unique key | not null | foreign key |
| --- | --- | --- | --- | --- | --- |
| id | bigint | ◯ |  | ◯ |  |
| user_id | bigint |  |  | ◯ |  |
| date | date |  |  | ◯ |  |
| check_in | datetime |  |  |  |  |
| check_out | datetime |  |  |  |  |
| remarks | string |  |  |  |  |
| status | string |  |  |  |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

### break_timesテーブル
| カラム名 | 型 | primary key | unique key | not null | foreign key |
| --- | --- | --- | --- | --- | --- |
| id | bigint | ◯ |  | ◯ |  |
| attendance_id | unsigned bigint |   |   | ◯ | attendances(id) |
| break_start | datetime |    |  |  |  |
| break_end | datetime |    |  |  |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

### attendance_requestsテーブル
| カラム名 | 型 | primary key | unique key | not null | foreign key |
| --- | --- | --- | --- | --- | --- |
| id | bigint | ◯ |  | ◯ |  |
| user_id | unsigned bigint |   |   | ◯ | user(id) |
| attendance_id | unsigned bigint |   |   | ◯ | attendances(id) |
| requested_check_in | datetime |    |  |  |  |
| requested_check_out | datetime |    |  |  |  |
| reason | string |    |  |  |  |
| status | string |    |  |  |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

### attendance_request_break_timesテーブル
| カラム名 | 型 | primary key | unique key | not null | foreign key |
| --- | --- | --- | --- | --- | --- |
| id | bigint | ◯ |  | ◯ |  |
| attendance_request_id | unsigned bigint |   |   | ◯ | attendance_request(id) |
| break_start | datetime |    |  |  |  |
| break_end | datetime |    |  |  |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |


## ER図添付  
 ![alt]<img src=".drawio.png">

## 使用技術 
* PHP:8.1.33 
* Lravel:8.83.8 
* MySQL:8.0.26 
* nginx:1.21.1 
* MailTrap

## 開発環境 
* 一般用ログイン:http://localhost/login  
* 管理者用ログイン:http://localhost/admin/login  
* phpmyadmin:http://localhost:8080  
* MailTrap:https://mailtrap.io  

### 備考  
* 今回のテストデータは2つあります。
    * テストデータ1  一般ユーザー用
    * テストデータ2  管理者用  

* ログインする際、以下のログインパスワードでログインしてください。　
    * テストデータ1  
        ```bash  
        メールアドレス:  test@example.com    
        パスワード:      password   
        ```
    * テストデータ2  
        ```bash  
        メールアドレス:  test1@example.com    
        パスワード:      password   
        ```
  


