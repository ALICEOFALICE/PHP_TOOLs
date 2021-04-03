<?php //echo json_encode(array("name"=>"xie"));
#echo "<div style='text-align:center'>您查询的PID为：";
$UID = file_get_contents("php://input");
#$UID = 37958451;

$uid_home_info = (json_decode(file_get_contents("https://api.bilibili.com/x/space/acc/info?mid=" . $UID . "&jsonp=jsonp"), true));
$uid_live_medal = (json_decode(file_get_contents("https://api.live.bilibili.com/live_user/v1/UserInfo/get_weared_medal?uid=" . $UID), true));
$uid_home_follow = (json_decode(file_get_contents("https://api.bilibili.com/x/relation/stat?vmid=" . $UID . "&jsonp=jsonp"), true));
$post_data = array(
    'UID' => $UID,
    'FACE' => $uid_home_info["data"]["face"]
);
$uid_face=json_decode(img_post('http://localhost/down_img.php', $post_data),true);
$result_data = array(
    "code" => "200",
    "data" => array(

        "uid_home_info" => $uid_home_info,
        "uid_home_follow" => $uid_home_follow,
        "uid_live_medal" => $uid_live_medal,
        "uid_face"=>$uid_face,
    ),
);
echo json_encode($result_data, true);
function img_post($url, $img_post_data)
{
    $type = "application/json";
    $data = json_encode($img_post_data);
    $post_data["http"] = array(
        "timeout" => 30,
        "method" => "POST",
        "header" => "Content-Type:$type;charset=utf-8",
        "content" => $data,

    );
    $context = stream_context_create($post_data);
    return (file_get_contents($url,false,$context));
}

;


