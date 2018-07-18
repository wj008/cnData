<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Email 26029682@qq.com
 * Date: 18-7-18
 * Time: 上午2:14
 */
ini_set("max_execution_time", "300");
ini_set('memory_limit', '256M');
include 'word.php';


//数据库链接
$sqli = new mysqli('127.0.0.1', 'mytest', '123456', 'mytest1', 3306);
$sqli->query("SET NAMES utf8");
$sqli->query('start transaction');

//及时刷新缓存区显示进度-------
ob_implicit_flush(1);

//生成品牌数据
$sqli->query('CREATE TABLE IF NOT EXISTS `brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
');
$result = $sqli->query('SELECT COUNT(*) FROM brand');
$count = $result->fetch_row()[0];
if ($count == 0) {
    echo '---生成品牌数据' . PHP_EOL;
    $stmt = $sqli->prepare('INSERT INTO brand(`name`,`addtime`,`updatetime`) VALUE (?,?,?)');
    $stmt->bind_param("sss", $bname, $baddtime, $bupdatetime);
    $sqli->query('begin');
    for ($i = 0; $i < 3000; $i++) {
        $bname = '品牌' . ($i + 1);
        $baddtime = date('Y-m-d H:i:s', mt_rand(time() - 86400 * 365, time()));
        $bupdatetime = date('Y-m-d H:i:s', mt_rand(time() - 86400 * 365, time()));
        $stmt->execute();
    }
    $sqli->query('commit');
    $stmt->close();
}
//生成分类
$sqli->query('CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
');
$result = $sqli->query('SELECT COUNT(*) FROM category');
$count = $result->fetch_row()[0];
if ($count == 0) {
    echo '---生成分类数据' . PHP_EOL;
    $stmt = $sqli->prepare('INSERT INTO category(`name`,`addtime`,`updatetime`) VALUE (?,?,?)');
    $stmt->bind_param("sss", $cname, $caddtime, $cupdatetime);
    $sqli->query('begin');
    for ($i = 0; $i < 3000; $i++) {
        $cname = '分类' . ($i + 1);
        $caddtime = date('Y-m-d H:i:s', mt_rand(time() - 86400 * 365, time()));
        $cupdatetime = date('Y-m-d H:i:s', mt_rand(time() - 86400 * 365, time()));
        $stmt->execute();
    }
    $sqli->query('commit');
    $stmt->close();
}

//生成产品表
$sqli->query('CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `brand` int(11) DEFAULT NULL,
  `brandName` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `categoryName` varchar(255) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
');

//设置生成大小
$size = 1000000;
$progress = 0;
echo '---生成产品数据' . PHP_EOL;
$stmt = $sqli->prepare('INSERT INTO product(`name`,`brand`,`brandName`,`price`,`category`,`categoryName`,`addtime`,`updatetime`) VALUE (?,?,?,?,?,?,?,?)');
$stmt->bind_param("sisiisss", $name, $brand, $bname, $price, $category, $cname, $addtime, $updatetime);
for ($i = 0; $i < $size; $i++) {
    if ($i % 10000 == 0) {
        if ($i != 0) {
            $sqli->query('commit');
        }
        $sqli->query('begin');
        $p = floor($i * 100 / $size);
        if ($p > $progress) {
            $progress += 1;
            echo $i . '/' . $size . '---' . $progress . '%' . PHP_EOL;
        }
    }
    $name = Word::create(6);
    $brand = mt_rand(1, 3000);
    $price = mt_rand(1, 10000);
    $category = mt_rand(1, 6000);
    $bname = '品牌' . $brand;
    $cname = '分类' . $category;
    $addtime = date('Y-m-d H:i:s', mt_rand(time() - 86400 * 365, time()));
    $updatetime = date('Y-m-d H:i:s', mt_rand(time() - 86400 * 365, time()));
    $stmt->execute();
}
$sqli->query('commit');
$stmt->close();
echo '完成' . PHP_EOL;
