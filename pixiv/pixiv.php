<?php
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    $get_data =array(
        "key"=>$_GET["key"],
        "num"=>$_GET["num"],
    );
    $data=($get_data) ;
}
else{
    $post_data = json_decode(file_get_contents("php://input"), true);;
    $data=$post_data;
}
$key = ($data["key"]);
$mysql_config = json_decode(file_get_contents("./config.json"),true);
$con = mysqli_connect($mysql_config["host"], $mysql_config["username"], $mysql_config["password"], $mysql_config["database"]);
function random($num, $con)
{
    if ($num <= 0) {
        return (1);
    }
    $find_num_sql = "SELECT * FROM pixiv_day";
    $result = mysqli_query($con, $find_num_sql);
    $max_num = mysqli_num_rows($result);
    $pid_list = array();

    for ($i = 1; $i <= $num + 1; $i++) {
        $randNum = 0;
        $randNum = rand("1", $max_num);
        if (in_array($randNum, $pid_list)) {
            $i--;
            continue;
        }
        $find_pid_sql = "SELECT * FROM pixiv_day where ID=" . $randNum;
        $find_pid_data = mysqli_fetch_all(mysqli_query($con, $find_pid_sql));

        $pid_data = array(
            "pid" => $find_pid_data[0][1],
            "num" => $find_pid_data[0][2],
            "name" => $find_pid_data[0][3],
            "author" => $find_pid_data[0][4],
            "TAGS" => $find_pid_data[0][5],
            "img_big_link" => $find_pid_data[0][6],
            "img_original_link" => $find_pid_data[0][7],
            "width" => $find_pid_data[0][8],
            "height" => $find_pid_data[0][9],

        );
        array_push($pid_list, $pid_data);


    }
    $result_data = array(
        "code" => "200",
        "img_num" => $num,
        "time" => time(),
        "data" => $pid_list
    );
    return json_encode($result_data);

}

function key_check($key, $con, $data)
{
    $find_key_sql = "SELECT * FROM authorkey where apikey=" ."'". $key."'";
    $result = (mysqli_query($con, $find_key_sql));


    if (mysqli_num_rows($result) > 0) {
        echo(random($data["num"], $con));

    } else{
        $sq_faile = array(
            "code"=>403,
            "info"=>"您的授权码错误或不存在，请购买授权码后使用，购买地址见shop_url，使用方法见doc",
            "doc"=>"还没写",
            "shop_url"=>"https://shop.loli.fit"
        );
        print_r(json_encode($sq_faile),true);
    }


}

key_check($key, $con, $data);