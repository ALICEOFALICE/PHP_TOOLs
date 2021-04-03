<?php
$bili_json = json_decode(file_get_contents("php://input"),true);
#echo $bili_json["UID"];
function bili_img($bili_json){
    $header = (
        header("Referer:https://space.bilibili.com/")
    );
    $face_url = "./face/".$bili_json["UID"].".jpg";
    file_put_contents($face_url,file_get_contents($bili_json["FACE"],$header));
    $_result = array(
        "code"=>200,
        "UID"=>$bili_json["UID"],
        "face_url"=>$face_url,
    );
    header("Content-type: application/json");
    return ($_result);
}
echo json_encode(bili_img($bili_json),true);