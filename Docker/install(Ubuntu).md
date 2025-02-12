# DockerをUbuntuにインストールする方法

## 前準備
```
sudo apt update && apt upgrade -y
```
```
sudo apt install ca-certificates curl gnupg
```

## リポジトリを追加する
```
sudo install -m 0755 -d /etc/apt/keyrings
```
```
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
```
```
sudo chmod a+r /etc/apt/keyrings/docker.asc
```
```
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
```
```
sudo apt update
```

## Dockerエンジンをインストールする
```
sudo apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```
```
docker --version
```
インストールできたことを確認する。


## おまけ
### インストール先を変更する
`/etc/docker/daemon.json`を
```
{
          "data-root": "/srv/dev-disk-1/Docker"
}
```
のように編集することで可能です。編集したあとは
```
sudo systemctl restart docker
```
Dockerを再起動してください、


### リソースを制限できない
メモリサブシステムの有効化
`/etc/default/grub`のGRUB_CMDLINE_LINUX_DEFAULTに`cgroup_enable=memory`を追記
```
update-grub
```    
grubをアップデート  

```
reboot now
```    
再起動
