# Snap版Dockerをapt版Dockerに移行する方法
## 1.Snap版Dockerを削除
容量が大きいとスナップショットを作成するのに時間がかかるので無効化する(任意)
```
sudo snap set system snapshots.automatic.retention=no
```
Dockerを削除
```
sudo snap remove docker
```

## [2.APTでインストール](./install(Ubuntu).md)

## 3.システム再起動(必要)
システム再起動
```
sudo reboot 
'''
