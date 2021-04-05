<?php
error_reporting(0);
if($_SERVER["REQUEST_METHOD"] == "GET"){
    #print_r("get");
}else{
    $post_data = json_decode(file_get_contents("php://input"), true);;
    $data=$post_data;
}
$key = ($data["key"]);
$mysql_config = json_decode(file_get_contents("./config.json"),true);
$con = mysqli_connect($mysql_config["host"], $mysql_config["username"], $mysql_config["password"], $mysql_config["database"]);
function get_pixiv_url($PID){
    $PIXI_FIND_URL = "https://www.pixiv.net/ajax/illust/".$PID;
    $PIXIV_INFO = json_decode(file_get_contents($PIXI_FIND_URL),true);
    if ($http_response_header[0]=="HTTP/1.1 404 Not Found") {
        $result_data=array(
            "code"=>"404",
            "time"=>time(),
            "info"=>"找不到信息，可能是错误的UID",
        );
        return(json_encode($result_data,true));
    };
    #print_r($PIXIV_INFO["body"]["illustId"]) ;
    $result_data=array(
        "code"=>"200",
        "time"=>time(),
        "data"=>array(
            "name"=>$PIXIV_INFO["body"]["illustTitle"],
            "pid"=>$PIXIV_INFO["body"]["illustId"],
            "illustComment"=>$PIXIV_INFO["body"]["illustComment"],
            "createtime"=>$PIXIV_INFO["body"]["createDate"],
            "updatatime"=>$PIXIV_INFO["body"]["uploadDate"],
            "pixiv_url"=>$PIXIV_INFO["body"]["urls"],
            "tags"=>$PIXIV_INFO["body"]["tags"]["tags"],
            "width"=>$PIXIV_INFO["body"]["width"],
            "height"=>$PIXIV_INFO["body"]["height"],
            "pid_page"=>$PIXIV_INFO["body"]["pageCount"],
            "view_num"=>$PIXIV_INFO["body"]["viewCount"],
            "like_num"=>$PIXIV_INFO["body"]["likeCount"],

            "autor_info"=>array(
                "userName"=>$PIXIV_INFO["body"]["userName"],
                "userId"=>$PIXIV_INFO["body"]["userId"],
            )

        ),

    );
    return json_encode($result_data,true);
}
function key_check($key, $con, $data)
{
    $find_key_sql = "SELECT * FROM authorkey where apikey=" ."'". $key."'";
    $result = (mysqli_query($con, $find_key_sql));


    if (mysqli_num_rows($result) > 0) {
        echo get_pixiv_url($data["pid"]);

    } else{
        $sq_faile = array(
            "code"=>403,
            "info"=>"您的授权码错误或不存在，请购买授权码后使用，购买地址见shop_url，使用方法见doc",
            "doc"=>"还没写",
            "shop_url"=>"https://shop.loli.fit"
        );
        echo(json_encode($sq_faile));
    }


}
key_check($key, $con, $data);