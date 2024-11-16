Oyta PHP Common
===============

## 说明

* 插件仅支持PHP8.0+
* 虚拟币需求开启gmp扩展
* 纯静态调用
* 纯自用公共包

## 条目

* 特殊密码设置与比对
* 金额格式化-逗号分隔
* 各类汇率查询
* 随机数的生成
* 设置、获取、验证 谷歌令牌
* 生成BTC Taproot地址和查询交易记录
* 生成ETH地址、查询交易记录和转账
* 生成TRON地址、查询交易记录和转账
* 新浪股票数据
* 邮件发送

## 安装
~~~
composer require oyta/common
~~~

## 使用

#### 引入并继承类
~~~
use Oyta\Common\Common;
class Index extends Common{}
~~~


#### 特殊密码设置与比对
~~~
//设置密码
$pass = $this->setPass('123456');
$salt = $pass['salt'];
$password = $pass['pass'];

//比对密码
if($this->verPass($salt,'123456') != $password)
~~~


#### 金额格式化-逗号分隔
~~~
$m = 123456.123456
$mo = $this->setFotMoney($m,4);
//输出结果为 123,456.1234
~~~


#### 各类汇率查询
~~~
$rate = $this->getRate('cny'); //任何货币、虚拟币
$rate['usdt']; //得到人民币转换USDT的汇率
$rate['btc']; //得到人名币转换BTC的汇率
~~~


#### 随机数的生成
~~~
$this->setRound(5); //默认生成小写字母随机数
$this->setRound(5,'n'); //指派生成类型 n-数字 s-小写字母 c-大写字母 y-特殊符号 其余随意组合 例: ns或sn sc或cs
~~~


#### 设置、获取、验证 谷歌令牌
~~~
$gcode = $this->setGcode($name); //设置谷歌令牌 $name为令牌显示名称 如 $name = 'Oyta';也可以是 xxx-用户名
//返回 ['secret'=>密钥,'qrcode'=>二维码]
$this->getGcode($name,$secret); //通过密钥获取谷歌令牌 $name同上 $secret为设置生成的密钥
//返回同上
$data = $this->verGcode($secret,$code); //验证需要私钥与动态验证码
// $data 返回bool类型 true false
~~~


#### 生成BTC Taproot地址和查询交易记录
~~~
//直接生成
$this->getBtcAddress(); //返回私钥与地址

//通过私钥生成地址
$this->getBtcMultiAddress($privateKey); //返回私钥与地址

//查询BTC转账记录
$this->getBtcTransferRecord($address); //可做支付地址监听使用

//查询runes brc20 src20 arc20 ordinals_nft 转账记录
$address = '';  //地址
$token = '';    //铭文代币类型  runes brc20 src20 arc20 ordinals_nft
$tokenId = '';  //铭文代币ID
$limit = 20;    //可不填入  默认获取最新20条 最大可获取100条最新数据
$this->getBtcTokenTransferRecord($address,$token,$tokenId,$limit=20); //可做支付地址监听使用
~~~

#### 生成ETH地址、查询交易记录和转账
~~~
//直接生成
$this->getEthAddress();

//通过私钥生成
$this->getEthMultiAddress($privateKey);

//ETH转账记录
$this->getEthTransferRecord($address);

//ERC20-USDT转账记录
$this->getEthTokenTransferRecord($address);

//ETH转账
$priv //发送者私钥
$from //发送者地址
$to //接收者(收款地址)
$money //发送金额 '0.001'
$this->EthTransfer($priv,$from,$to,$money);

//ETH代币转账
$priv //发送者私钥
$from //发送者地址
$to //接收者(收款地址)
$money //发送金额 '0.001'
$token //代币合约地址
$this->EthTokenTransfer($priv,$from,$to,$money,$token);
~~~


#### 生成TRON地址、查询交易记录和转账
~~~
//直接生成
$this->getTronAddress();

//通过私钥生成
$this->getTronMultiAddress($privateKey);

//查询TRX转账记录
$this->getTronTransferRecord($address);

//查询TRC20代币转账记录
$this->getTronTokenTransferRecord($address); //默认查询TRC20-USDT
$this->getTronTokenTransferRecord($address,$token = '任意TRC20代币地址');

//查询转账HASH
$this->getTronHashTransferRecord($address,$hash);

//TRX转账
$priv //发送者私钥
$from //发送者地址
$to //接收者(收款地址)
$money //发送金额 '0.001'
$this->TronTransfer($priv,$from,$to,$money);

//TRC20代币转账
$priv //发送者私钥
$from //发送者地址
$to //接收者(收款地址)
$money //发送金额 '0.001'
$token //代币合约地址
$this->TronTokenTransfer($priv,$from,$to,$money,$token);
~~~

#### 新浪股票数据
~~~
//获取新浪股票实时数据
$this->getSinaList('000001');

//获取新浪股票历史K线数据
$code //股票代码
$scale //时间 5,15,30,60 分钟 
$datalen //数据长度 默认1023 自定义
$this->getSinaTraderHistory($code, $scale);
$this->getSinaTraderHistory($code, $scale, $datalen=500);
~~~

#### 邮件发送
~~~
$host //服务器地址
$user //登陆账号
$pass //授权码
$ssl //使用ssl加密方式登录鉴权
$port //ssl连接smtp服务器的远程服务器端口号
$email //发件人邮箱
$name //发件人名称
$to //收件人邮箱地址
$title //邮件的主题
$content //邮件的内容
$this->SendEmail($host, $user, $pass, $ssl, $port, $email, $name, $to, $title, $content);
~~~

## 命名规范

`Oyta PHP Common`遵循PSR-2命名规范和PSR-4自动加载规范。
