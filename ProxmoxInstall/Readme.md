# ProxmoxVEのインストール方法
Proxmoxはオープンソースのハイパーバイザーです。

## 1.ProxmoxOSのインストール

### 1-1.OSをダウンロード
[ProxmoxVE](https://www.proxmox.com/en/products/proxmox-virtual-environment/overview)  
上記からisoファイルをダウンロード
### 1-2.書き込み
balenaEtcherを用いてisoファイルをUSBメモリに書き込む  
[balenaEtcher](https://etcher.balena.io/)
### 1-3.USBをインストール先のPCに接続しUSBメモリから起動する  
**推奨項目**
- ブートモードをUEFIにする(CMSを無効化)
- ErP Readyなどの省電力関係のものを無効化する
- 仮想化関係の項目を有効に
**起動できない場合**
- BIOSでUSBが有効になっているか
- BIOSでレガシーUSBが有効になっているか
- BIOSの起動順でその他のものよりUSBメモリが上位に来ているか
- セキュアブートの無効化を試す
### 1-4.起動したらセットアップユーティリティに従ってセットアップ  
**セットアップでエラーが起きる**
- BIOSでHDDを認識してる?
- BIOSのCPU設定のvt-x,vd-tが有効になっているか
- AMDの場合はSVM-Mode(AMD-V)が有効になっているか
※一部の古いプロセッサやAtom系列の仮想化非対応プロセッサにはインストールできない
**IPアドレスに関して**
アドレス:そのパソコンのIP
ゲートウェイ:ルータのIP
ネームサーバー(DNSServer):ルータのIPまたは8.8.8.8 or 1.1.1.1


## 2.Proxmoxの初期設定
ProxmoxのWeb管理画面にてこれらを実行する(コマンドの場合はssh接続可)  
管理画面のURLはProxmoxをインストールしたPCのIPの8006番です。http**s**でアクセスしてください。(Proxmoxをインストールしたの画面にURL表示されています)  
### 2-1.LV pve/dataの削除
ProxmoxではOS用(iSOやバックアップ保持用)の領域である`/dev/pve/root`とVMのディスクイメージを保存するための`/dev/pve/data`があります。  
リソース活用の観点から小規模なシステムである場合、これらを統合したほうがよいです。(あくまで個人の感想です)  
※デメリット:ストレージが満杯になった場合にホストマシンにも影響がでる  
LVを確認
```
lvdisplay
```
LV(pve-data)を削除
```
lvremove -f pve/data
```
LV(pve-root)を拡張
```
lvextend -l +100%FREE /dev/pve/root
```
ファイルシステムを拡張
```
resize2fs /dev/pve/root
```
### 2-2.クラスタに参加する
参加先のWebUIを開き。右のリストの「データセンター/クラスタ」から「join情報」を開き、参加させたいWebUIの同じ画面から「クラスタに参加」を選択し、先ほどコピーしたものを貼り付ける。  
**※Proxmoxクラスタが正常に動作するには過半数のノード(PC)が動作している必要があります。**  
**既存のノードをクラスタから削除する方法**
```
pvecm delnode {ノード名}
# 例:pvecm delnode pve10
```
```
rm -rf /etc/pve/nodes/{ノード名}
# 例:rm -rf /etc/pve/nodes/pve10
```
**ホスト名を変更する方法**
`/etc/hostname`と`/etc/hosts`を編集
例:/etc/hostname
```
pve10
```
例:/etc/hosts
```
127.0.0.1 localhost.localdomain localhost
192.168.11.210 pve10.local pve10

# The following lines are desirable for IPv6 capable hosts

::1     ip6-localhost ip6-loopback
fe00::0 ip6-localnet
ff00::0 ip6-mcastprefix
ff02::1 ip6-allnodes
ff02::2 ip6-allrouters
ff02::3 ip6-allhosts
```
