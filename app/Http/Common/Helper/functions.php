<?php
/**
 * Created by PhpStorm.
 * User: david you
 * Date: 2023/7/22
 * Time: 9:27
 */

if(!function_exists('showMsg'))
{
    /**
     * 公用的方法 返回json数据，进行信息的提示
     * @param $status 状态
     * @param string $message 提示信息
     * @param array $data 返回数据
     */
    function showMsg($status,$message = '',$data = []){
        $result = [
            'status' =>  $status,
            'message' => $message,
            'data' => $data
        ];
        exit(json_encode($result));
    }
}

if(!function_exists('getSiteUr')) {
    /**
     * 获取网站根路径
     * @return string
     */
    function getSiteUrl()
    {
        $http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
        return $http . '://' . $_SERVER['HTTP_HOST'];
    }
}

if(!function_exists('convert_arr_key')) {
    /**
     * @param $arr
     * @param $key_name
     * @return array
     * 将数据库中查出的列表以指定的 id 作为数组的键名
     */
    function convert_arr_key($arr, $key_name)
    {
        $arr2 = array();
        foreach ($arr as $key => $val) {
            $arr2[$val[$key_name]] = $val;
        }
        return $arr2;
    }
}

if(!function_exists('objectToArray')) {
    function objectToArray($array)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = objectToArray($value);
            }
        }
        return $array;
    }
}

/**
 * 多个数组的笛卡尔积
 *
 * @param unknown_type $data
 */
function combineDika() {
    $data = func_get_args();
    $data = current($data);
    $cnt = count($data);
    $result = array();
    $arr1 = array_shift($data);
    foreach($arr1 as $key=>$item)
    {
        $result[] = array($item);
    }

    foreach($data as $key=>$item)
    {
        $result = combineArray($result,$item);
    }
    return $result;
}



/**
 * 两个数组的笛卡尔积
 * @param unknown_type $arr1
 * @param unknown_type $arr2
 */
function combineArray($arr1,$arr2) {
    $result = array();
    foreach ($arr1 as $item1)
    {
        foreach ($arr2 as $item2)
        {
            $temp = $item1;
            $temp[] = $item2;
            $result[] = $temp;
        }
    }
    return $result;
}

//检测手机号格式是否正确
function check_tel($tel){
    if(preg_match("/^1[0-9]\d{9}$/", $tel)){
        return true;
    }
    return false;
}

//随机整数字符串
function randString($length=6){
    $str = '0123456789';
    $len = strlen($str);

    $ext = '';
    for($i = 0;$i<$length;$i++){
        $num = rand(0,$len-1);
        $ext .= $str[$num];
    }
    return $ext;
}


/**
 * 获取数组中的某一列
 * @param type $arr 数组
 * @param type $key_name  列名
 * @return type  返回那一列的数组
 */
function get_arr_column($arr, $key_name)
{
    $arr2 = [];
    foreach($arr as $key => $val){
        $arr2[] = $val[$key_name];
    }
    return $arr2;
}


/**
 * 获取随机字符串
 * @param int $randLength  长度
 * @param int $addtime  是否加入当前时间戳
 * @param int $includenumber   是否包含数字
 * @return string
 */
function get_rand_str($randLength=6,$addtime=1,$includenumber=0){
    if ($includenumber){
        $chars='abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
    }else {
        $chars='abcdefghijklmnopqrstuvwxyz';
    }
    $len=strlen($chars);
    $randStr='';
    for ($i=0;$i<$randLength;$i++){
        $randStr.=$chars[rand(0,$len-1)];
    }
    $tokenvalue=$randStr;
    if ($addtime){
        $tokenvalue=$randStr.time();
    }
    return $tokenvalue;
}

/**
 * CURL请求
 * @param $url 请求url地址
 * @param $method 请求方法 get post
 * @param null $postfields post数据数组
 * @param array $headers 请求header信息
 * @param bool|false $debug  调试开启 默认false
 * @return mixed
 */
function httpRequest($url, $method="GET", $postfields = null, $headers = array(), $debug = false) {
    $method = strtoupper($method);
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Safari/537.36 Core/1.70.3877.400 QQBrowser/10.8.4506.400");
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
    curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ci, CURLOPT_REFERER, 'https://lishi.tianqi.com/yanan/201911.html');//模拟来路
    switch ($method) {
        case "POST":
            curl_setopt($ci, CURLOPT_POST, true);
            if (!empty($postfields)) {
                $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
            }
            break;
        default:
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
            break;
    }
    $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;

    //echo $ssl;die();
    curl_setopt($ci, CURLOPT_URL, $url);
    if($ssl){
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
    }
    curl_setopt($ci, CURLOPT_HEADER, false); /*启用时会将头文件的信息作为数据流输出*/
    curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ci, CURLOPT_MAXREDIRS, 1);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLINFO_HEADER_OUT, true);
    //curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); //* *COOKIE带过去** */
    $response = curl_exec($ci);
    $requestinfo = curl_getinfo($ci);
    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    if ($debug) {
        echo "=====post data======\r\n";
        var_dump($postfields);
        echo "=====info===== \r\n";
        print_r($requestinfo);
        echo "=====response=====\r\n";
        print_r($response);
    }
    curl_close($ci);
    //return $response;
    return array($http_code, $response,$requestinfo);
}


/**
 * 获取远程或本地文件信息
 * @param  string   $strUrl     远程文件或本地文件地址
 * @param  integer  $intType    调用方式(1:get_headers 2:fsocketopen 3:curl 4:本地文件)
 * @param  array    $arrOptional
 * @return array
 * @author mengdj<mengdj#outlook.com>
 */
function remoteFileSize($strUrl,$intType=1,$arrOptional=array()){
    $arrRet=array(
        "length"=>0,                    //大小，字节为单位
        "mime"=>"",                     //mime类型
        "filename"=>"",                 //文件名
        "status"=>0                     //状态码
    );
    switch($intType){
        case 1:
            //利用get_headers函数
            if(($arrTmp=get_headers($strUrl,true))){
                $arrRet=array("length"=>$arrTmp['Content-Length'],"mime"=>$arrTmp['Content-Type']);
                if(preg_match('/filename=\"(.*)\"/si',$arrTmp['Content-Disposition'],$arr)){
                    $arrRet["filename"]=$arr[1];
                }
                if(preg_match('/\s(\d+)\s/',$arrTmp[0],$arr)){
                    $arrRet["status"]=$arr[1];
                }
            }
            break;
        case 2:
            //利用fsocket
            if(($arrUrl=parse_url($strUrl))){
                if($fp=@fsockopen($arrUrl['host'],empty($arrUrl['port'])?80:$arrUrl['port'],$error)){
                    @fputs($fp,"GET ".(empty($arrUrl['path'])?'/':$arrUrl['path'])." HTTP/1.1\r\n");
                    @fputs($fp,"Host: $arrUrl[host]\r\n");
                    @fputs($fp,"Connection: Close\r\n\r\n");
                    while(!feof($fp)){
                        $tmp=fgets($fp);
                        if(trim($tmp)==''){
                            //此行代码只读到头信息即可
                            break;
                        }else{
                            (preg_match('/(HTTP.*)(\s\d{3}\s)/',$tmp,$arr))&&$arrRet['status']=trim($arr[2]);
                            (preg_match('/Content-Length:(.*)/si',$tmp,$arr))&&$arrRet['length']=trim($arr[1]);
                            (preg_match('/Content-Type:(.*)/si',$tmp,$arr))&&$arrRet['mime']=trim($arr[1]);
                            (preg_match('/filename=\"(.*)\"/si',$tmp,$arr))&&$arrRet['filename']=trim($arr[1]);
                        }
                    }
                    @fclose($fp);
                }
            }
            break;
        case 3:
            //利用curl
            if(($ch=curl_init($strUrl))){
                curl_setopt($ch,CURLOPT_HEADER,1);
                curl_setopt($ch,CURLOPT_NOBODY,1);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                if(isset($arrOptional['user'])&&isset($arrOptional['password'])){
                    $headers=array('Authorization: Basic '.base64_encode($arrOptional['user'].':'.$arrOptional['password']));
                    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
                }
                $tmp=curl_exec($ch);
                curl_close($ch);
                (preg_match('/Content-Length:\s([0-9].+?)\s/',$tmp,$arr))&&$arrRet['length']=trim($arr[1]);
                (preg_match('/Content-Type:\s(.*)\s/',$tmp,$arr))&&$arrRet['mime']=trim($arr[1]);
                (preg_match('/filename=\"(.*)\"/i',$tmp,$arr))&&$arrRet['filename']=trim($arr[1]);
                (preg_match('/(HTTP.*)(\s\d{3}\s)/',$tmp,$arr))&&$arrRet['status']=trim($arr[2]);
            }
            break;
        case 4:
            //本地处理
            if(file_exists($strUrl)) {
                $arrRet=array(
                    "length"=>filesize($strUrl),
                    "mime" =>mime_content_type($strUrl),
                    "filename"=>basename($strUrl),
                    "status"=>200
                );
            }else{
                $arrRet=array(
                    "length"=>0,
                    "mime" =>'',
                    "filename"=>basename($strUrl),
                    "status"=>404
                );
            }
            break;
    }
    if(isset($arrOptional['getimagesize'])&&$arrRet['status']=='200'){
        if(($arrTmp=@getimagesize($strUrl))){
            $arrRet['width']=$arrTmp[0];
            $arrRet['height']=$arrTmp[1];
            $arrRet['type']=$arrTmp[2];
            $arrRet['tag']=$arrTmp[3];
            $arrRet['bits']=$arrTmp['bits'];
            $arrRet['channels']=$arrTmp['channels'];
            !isset($arrRet['mime'])&&$arrRet['mime']=$arrTmp['mime'];
        }
    }
    return $arrRet;
}


/**
 * 过滤数组元素前后空格 (支持多维数组)
 * @param $array array 要过滤的数组
 * @return array|string
 */
function trim_array_element($array){
    if(!is_array($array))
        return trim($array);
    return array_map('trim_array_element',$array);
}

/**
 * 检查手机号码格式
 * @param $mobile 手机号码
 */
function check_mobile($mobile){
    if(preg_match('/1[34578]\d{9}$/',$mobile))
        return true;
    return false;
}

/**
 * 检查固定电话
 * @param $mobile
 * @return bool
 */
function check_telephone($mobile){
    if(preg_match('/^([0-9]{3,4}-)?[0-9]{7,8}$/',$mobile))
        return true;
    return false;
}

/**
 * 检查邮箱地址格式
 * @param $email 邮箱地址
 */
function check_email($email){
    if(filter_var($email,FILTER_VALIDATE_EMAIL))
        return true;
    return false;
}


/**
 *   实现中文字串截取无乱码的方法
 */
function getSubstr($string, $start, $length) {
    if(mb_strlen($string,'utf-8')>$length){
        $str = mb_substr($string, $start, $length,'utf-8');
        return $str.'...';
    }else{
        return $string;
    }
}


/**
 * 判断当前访问的用户是  PC端  还是 手机端  返回true 为手机端  false 为PC 端
 * @return boolean
 */
/**
　　* 是否移动端访问访问
　　*
　　* @return bool
　　*/
function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;

    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile');
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            return true;
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        }
    }
    return false;
}

/**
 * 判断是否是微信浏览器
 * @return bool
 */
function is_weixin(){
    /*
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        return true;
    }
    */

    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false || isset($_SERVER['HTTP_SEC_FETCH_SITE']) ){
        return true;
    }
    return false;
}

/**
 * 判断是否为qq
 * @return bool
 */
function is_qq() {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'QQ') !== false) {
        return true;
    } return false;
}

/**
 * 判断是否为支付宝
 * @return bool
 */
function is_alipay() {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
        return true;
    } return false;
}

/**
 * 判断是否为ios
 * @return bool
 */
function is_ios()
{
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
        return true;
    }
    return false;
}


//随机生成唯一订单号
function getStrRand(){
    $order_no = date('Ymd').substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(1000, 9999));
    return $order_no;
}


//判断有没有图片被上传
function hasImage($imagename){
    foreach($_FILES[$imagename]['error'] as $v){
        if($v == 0){
            return true;
        }
    }
    return false;
}


//重组$_files的值去除空的name
function resetFiles($name = ''){
    $data = [];
    $files = $_FILES;
    foreach ($files as $key => $file) {
        if (is_array($file['name'])) {
            $item = [];
            $keys = array_keys($file);
            $count = count($file['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($file['error'][$i] == 0) {
                    $data['name'][] =  $file['name'][$i];
                    $data['type'][] =  $file['type'][$i];
                    $data['tmp_name'][] =  $file['tmp_name'][$i];
                    $data['error'][] =  $file['error'][$i];
                    $data['size'][] =  $file['size'][$i];
                }
            }
        }
    }
    $data2[$name] = $data;
    return $data2;
}


/**
 * 获取url 中的各个参数  类似于 pay_code=alipay&bank_code=ICBC-DEBIT
 * @param type $str
 * @return type
 */
function parse_url_param($str){
    $data = [];
    $str = explode('?',$str);
    $str = end($str);
    $parameter = explode('&',$str);
    foreach($parameter as $val){
        $tmp = explode('=',$val);
        $data[$tmp[0]] = $tmp[1];
    }
    return $data;
}


/**
 * 二维数组排序
 * @param $arr
 * @param $keys
 * @param string $type
 * @return array
 */
function array_sort($arr, $keys, $type = 'desc')
{
    $key_value = $new_array = [];
    foreach ($arr as $k => $v) {
        $key_value[$k] = $v[$keys];
    }
    if ($type == 'asc') {
        asort($key_value);
    } else {
        arsort($key_value);
    }
    reset($key_value);
    foreach ($key_value as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}


/**
 * 多维数组转化为一维数组
 * @param 多维数组
 * @return array 一维数组
 */
function array_multi2single($array)
{
    static $result_array = [];
    foreach ($array as $value) {
        if (is_array($value)) {
            array_multi2single($value);
        } else
            $result_array [] = $value;
    }
    return $result_array;
}


/**
 * 导出excel
 * @param $strTable	表格内容
 * @param $filename 文件名
 */
function downloadExcel($strTable,$filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".$filename."_".date('Y-m-d').".xls");
    header('Expires:0');
    header('Pragma:public');
    echo '<html><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$strTable.'</html>';
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}



function readFileFromDir($dir) {
    static $dir_file = [];

    if (!is_dir($dir)) {
        return false;
    }
    //打开目录
    $handle = opendir($dir);
    while (($file = readdir($handle)) !== false) {
        //排除掉当前目录和上一个目录
        if ($file == "." || $file == "..") {
            continue;
        }

        $file = $dir . DIRECTORY_SEPARATOR . $file;
        //如果是文件就打印出来，否则递归调用
         if (is_file($file) && strpos($file,"登记证") !==false) {
             //print $file . '';
             $dir_file[] = $file;

         } elseif (is_dir($file)) {
             readFileFromDir($file);
         }
    }

    return $dir_file;
}


// PHP正则验证车牌,验证通过返回true,不通过返回false
function isLicensePlate($str) {
    $pattern = "/^(([京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼使领][A-Z](([0-9]{5}[DF])|([DF]([A-HJ-NP-Z0-9])[0-9]{4})))|([京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼使领][A-Z][A-HJ-NP-Z0-9]{4}[A-HJ-NP-Z0-9挂学警港澳使领]))$/u";
    if(preg_match($pattern, $str)){
        return true;
    }
    return false;
}

