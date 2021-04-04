<?php
$post_data = json_decode(file_get_contents("php://input"),true);
$con = mysqli_connect("rm-j6c8f20c4w5f1f842xo.mysql.rds.aliyuncs.com","pixiv_api","Xcw10086","pixiv");
$sq_data=array(
    "NAME"=>"XIE",
    "MAIL"=>"q1662053777@outlook.com",
    "TIME"=>"1617465600",
    "KEY"=>"45767478984"
);

#echo $sq_data;
function add_author($sq_data,$con){
    $big = "('".$sq_data["NAME"]."',"."'".$sq_data["MAIL"]."',"."'".$sq_data["TIME"]."',"."'".$sq_data["KEY"]."')";
    $add_authorkey_sql = "INSERT INTO authorkey(NAME,MAIL,TIME,apikey)VALUE ".$big;
    $result = mysqli_query($con,$add_authorkey_sql);
    print_r($add_authorkey_sql) ;
}
add_author($sq_data,$con);