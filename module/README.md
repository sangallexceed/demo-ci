#開発環境について

##起動と接続
###1. サーバーの構築と起動
- イメージのビルドとイメージのプルを行った後、起動
```
$ start.sh
```
- 全コンテナをまとめて終了
```
$ stop.sh
```
- その他
```
全コンテナをまとめて削除
$ docker-compose rm
イメージのビルドだけする場合
$ docker-compose build
イメージのプルだけする場合
$ docker-compose pull
```

###2. サーバーへの接続
####2.1. APPサーバーへブラウザでアクセス
- hostsファイルに下記記述を追加
```
127.0.0.1 dev
```
- [http://dev/](http://dev/) へアクセス

####2.2. サーバーへSSHでアクセス
```
APPサーバー
$ connect-mvno-app.sh

DBサーバー
$ connect-mvno-db.sh
```

####2.3. DBサーバーへの接続
```
Host: 127.0.0.1
Username: docker
Passowrd: docker
Database: dokcer
Port: 3306
```




***
##Dockerfile作成時に役立つコマンド集
- Dockerfile作成用
```
$ docker pull centos:7
$ docker run -it -v [マウント元]:[マウント先] -p 80:80 -p 3:443 centos:7 /bin/bash
$ docker run -it -v /Users/eyemovic/Projects/mvno/module/srv:/srv -p 80:80 -p 443:443 centos:7 /bin/bash
```
- イメージをビルド
```
$ docker build -t nginx-php ./
```
- ビルドしたイメージの確認＆起動
```
$ docker images
$ docker run -d --name mvno-module -v [ホスト絶対パス]:[ゲスト絶対パス] -p [ポート]: [ポート] [イメージID]
$ docker run -d --name mvno-module -v /Users/eyemovic/Projects/mvno/module/srv:/srv -p 80:80 -p 443:443 [イメージID]
$ docker run -d --name mvno-module -v /Users/eyemovic/Projects/mvno/module/srv:/srv -p 80:80 -p 443:443 645bdc7d822f
```
- 起動中のコンテナに接続
```
$ docker ps
$ docker exec -it [コンテナID] /bin/bash
```
- コンテナの停止
```
$ docker ps
$ docker stop [コンテナID]
```
- 停止中のコンテナすべて削除
```
$ docker ps -a
$ docker rm [コンテナID]
$ docker rm $(docker ps -a --filter 'status=exited' -q)
```
- イメージを削除
```
$ docker images
$ docker rmi [イメージID]
```
- コンテナを削除
```
$ docker ps -a
$ docker rm [コンテナID]
```
- ファイルのコピー
```
$ docker ps
$ docker cp [コピー元] [コンテナID]:[コピー先]
$ docker cp etc/nginx/conf.d/vhosts.conf 609ce370f2b1:/etc/nginx/conf.d/

もしくはコンテナ名
$ docker cp [コピー元] [コンテナ名]:[コピー先]
```
- デフォルトブリッジネットワークを参照する
```
$ docker network inspect bridge
```
- ファイルをコピーして実行
```
docker cp etc/nginx/conf.d/vhosts.conf 609ce370f2b1:/etc/nginx/conf.d/
docker exec -it mvno-db ps -ef
```

