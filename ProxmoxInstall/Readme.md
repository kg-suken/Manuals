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
### 2-3.エンタープライズのリポジトリを削除
参加先のWebUIを開き。右のリストの「ノード名(pve10)/アップデート/リポジトリ」を選択しコンポーネントが「enterprise」または「pve-enterprise」となっているものをすべて無効にする

### 2-4.No-Subscriptionリポジトリを追加
2-3と同じ画面で「追加」を押し、「No-Subscription」を追加する  
Cephを使う場合は「Ceph Quincy No-Subscription」と「Ceph Reef No-Subscription」も追加する
2-3も含めてすべてのノード(PC)に対して行ってください。

### tsoを無効化する
tsoはパケットの分割をNICデバイスで行いCPUの負荷を軽減する仕組みです。これが悪さをし、NICデバイスをハングアップさせるので無効化しましょう。  
※使っていて問題がなければこの操作は必要ありません。
現在の状態を確認
```
ethtool -k {デバイス名} | grep tcp-segmentation-offload
#例:ethtool -k eno1 | grep tcp-segmentation-offload
```
デバイス名はWebUIの「ノード名(pve10)/システム/ネットワーク」から確認できます。  
`tcp-segmentation-offload: off`ならばすでに無効になっています。

無効にする場合は
`/etc/network/interfaces`を編集し物理I/Fのところにoffload-* offを追記。
```
  offload-gso off
  offload-gro off
  offload-tso off
  offload-rx off
  offload-tx off
  offload-rxvlan off
  offload-txvlan off
  offload-sg off
```
再起動して変更を適用し、サイド確認コマンドをじっこうし、offになっているかを確認してください。

## その他
### PCIeデバイスをパススルーしたい
[参考文献](https://qiita.com/disksystem/items/0879f379e2bbc7a08675)
BIOSでIOMMUが有効になっているかを確認し、`/etc/default/grub`を編集。  
```
#Before
GRUB_CMDLINE_LINUX_DEFAULT="quiet"
#After(Intelの場合)
GRUB_CMDLINE_LINUX_DEFAULT="quiet intel_iommu=on iommu=pt video=efifb:off"
#After(AMDの場合)
GRUB_CMDLINE_LINUX_DEFAULT="quiet amd_iommu=on iommu=pt video=efifb:off"
```
`iommu=pt`もしているするとパフォーマンスが上がるらしい
例:
```
GRUB_CMDLINE_LINUX_DEFAULT="quiet intel_iommu=on iommu=pt"
```
grubをアップデートする
```
update-grub
```
proxmox-boot-toos
```
proxmox-boot-tool refresh
```
※systemd-boot の場合: /etc/kernel/cmdline を編集
再起動して適用し、
```
dmesg | grep -e DMAR -e IOMMU
```
IOMMU enabled のような出力があれば有効になっている。  
※grubの設定ミスで起動しなくなった場合は、ブート直後の選択画面で[eキー]を押してgrubを編集してください。

### 1つの一般ユーザー向けGPUを複数のVMで利用したい
セキュアブートを無効化し
[vGPU Unlock RS](https://gitlab.com/polloloco/vgpu-proxmox)に従ってください。  
※当方では成功いたしませんでした。

### Proxmoxのバーナーロゴを変更したい
`/usr/share/pve-manager/images/proxmox_logo.png`  
を変更する。アップデートで消えるので注意。
### コンソールログイン時に表示される文字列を変更したい
`/etc/motd`  
を変更する。  
例:  
```
 ______     _______ _  ___  
|  _ \ \   / / ____/ |/ _ \ 
| |_) \ \ / /|  _| | | | | |
|  __/ \ V / | |___| | |_| |
|_|     \_/  |_____|_|\___/ 
                            
サーバー10号機

The programs included with the Debian GNU/Linux system are free software;
the exact distribution terms for each program are described in the
individual files in /usr/share/doc/*/copyright.



Debian GNU/Linux comes with ABSOLUTELY NO WARRANTY, to the extent
permitted by applicable law.
```
※例のようなアスキーアート(?)はfigletで作成できます。


## 制作
**sskrc**

---

今後の改良に関する提案やバグ報告は、お気軽にIssueを通してご連絡ください。
