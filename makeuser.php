<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Email 26029682@qq.com
 * Date: 18-7-19
 * Time: 上午12:52
 */
ini_set("max_execution_time", "300");
ini_set('memory_limit', '256M');
include 'name.php';

//数据库链接
$sqli = new mysqli('127.0.0.1', 'root', '123456', 'mytest1', 3306);
$sqli->query("SET NAMES utf8");
$sqli->query('start transaction');

//及时刷新缓存区显示进度-------
ob_end_clean();
ob_implicit_flush(1);

//生成用户表数据
$sqli->query('CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `sex` int(1) DEFAULT 0,
  `age` int(2) DEFAULT 0,
  `grade` int(11) DEFAULT 0,
  `addtime` datetime DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
');


//设置生成大小
$size = 1000000;
$progress = 0;
echo '---生成用户数据' . PHP_EOL;
$stmt = $sqli->prepare('INSERT INTO member(`username`,`password`,`name`,`sex`,`age`,`grade`,`addtime`,`updatetime`) VALUE (?,?,?,?,?,?,?,?)');
$stmt->bind_param("sssiiiss", $username, $password, $name, $sex, $age, $grade, $addtime, $updatetime);
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
    $username = Name::getUserName(6);
    $password = '123456';
    $name = Name::create();
    $sex = mt_rand(1, 2);
    $age = mt_rand(1, 100);
    $grade = mt_rand(1, 20);
    $addtime = date('Y-m-d H:i:s', mt_rand(time() - 86400 * 365, time()));
    $updatetime = date('Y-m-d H:i:s', mt_rand(time() - 86400 * 365, time()));
    $stmt->execute();
}
$sqli->query('commit');
$stmt->close();
echo '完成' . PHP_EOL;
