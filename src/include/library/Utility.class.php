<?php
/**
 * @author: admin@wroupon.com
 */
class Utility
{
    const CHAR_MIX = 0;
    const CHAR_NUM = 1;
    const CHAR_WORD = 2;

    public static function Option($a=array(), $v=null, $all=null)
    {
        $option = null;
        if ( $all ){
            $selected = ($v) ? null : 'selected';
            $option .= "<option value='' $selected>".strip_tags($all)."</option>";
        }

        $v = explode(',', $v);
        settype($v, 'array');
        foreach( $a AS $key=>$value )
        {
            if (is_array($value)) {
                $key = strval($value['id']);
                $value = strval($value['name']);
            }
            $selected = in_array($key, $v) ? 'selected' : null;
            $option .= "<option value='{$key}' {$selected}>".strip_tags($value)."</option>";
        }
        return $option;
    }

	public static function ValidEmail($email, $strict=false) {
		$regexp = '/^[\w\-\.]+@[\w\-]+(\.[\w\-]+)*(\.[a-zA-Z]{2,})$/';
		if ( preg_match($regexp, $email) ){
			if (strstr(strtoupper(PHP_OS),'WIN')) {
				return true;
			}
			list ($user,$domain) = explode('@', $email, 2);
			if ( $strict && !gethostbyname($domain)
					&& !getmxrr($domain,$mxhosts) ){
				return false;
			}
			return true;
		}
		return false;
	}

	public static function ArrayFilter($a, $need_keys=array()) {
		$r = array();
        foreach($need_keys AS $k) {
            if (isset($a[$k])) $r[$k] = $a[$k];
        }
        return $r;
    }

    public static function CommaTips($a=array(), $v=null) {
        $cval = array();
        if (is_string($v)) {
            $v = preg_split('/[\s,]+/', $v, -1, PREG_SPLIT_NO_EMPTY);
        }
        settype($v, 'array');
        foreach($a AS $key=>$value) {
            if (in_array($key, $v)) {
                if (is_array($value)) {
                    $cval[] = $value['name'];
                } else {
                    $cval[] = $value;
                }
            }
        }
        return join(',', $cval);
    }

    public static function CheckBox($a, $v='', $n='cb', $m=null) {
        $cbox = null;
        if (is_string($v)) $v = preg_split('/[\s,]+/', $v, -1, PREG_SPLIT_NO_EMPTY);
        settype($v, 'array');
        foreach($a AS $key=>$value) {
            if (is_array($value)) {
                $key = strval($value['id']);
                $value = strval($value['name']);
            }
            $checked = in_array($key, $v) ? 'checked' : null;
            $readonly = $m=='fix'?'onclick="return false;"':null;
            $checked = $m=='fix'?'checked':$checked;
            $cbox .= "<li><input type='checkbox' name='{$n}[]' value='{$key}' {$checked} {$readonly} />&nbsp;{$value}</li>";
        }
        return $cbox;
    }

    public static function RadioButton($a=array(), $v=null, $n='cb') {
        $cbox = null;
        if (is_string($v)) $v = preg_split('/[\s,]+/', $v, -1, PREG_SPLIT_NO_EMPTY);
        settype($v, 'array');
        foreach($a AS $key=>$value) {
            if (is_array($value)) {
                $key = strval($value['id']);
                $value = strval($value['name']);
            }
            $checked = in_array($key, $v) ? 'checked' : null;
            $cbox .= "<li><input type='radio' name='{$n}' value='{$key}' {$checked} />&nbsp;{$value}</li>";
        }
        return $cbox;
    }

    public static function OptionArray($a=array(), $c1, $c2) {
        if (empty($a)) return array();
        $s1 = self::GetColumn($a, $c1);
        $s2 = self::GetColumn($a, $c2);
        if ( $s1 && $s2 && count($s1)==count($s2) ) {
            return array_combine($s1, $s2);
        }
        return array();
    }

    public static function SortArray($a=array(), $s=array(), $key=null)
    {
        if ($key) $a = self::AssColumn($a, $key);
        $ret = array();
        foreach( $s AS $one )
        {
            if ( isset($a[$one]) )
                $ret[$one] = $a[$one];
        }
        return $ret;
    }

    public static function GetColumn($a=array(), $column='id', $null=true, $column2=null)
    {
        $ret = array();
        @list($column, $anc) = preg_split('/[\s\-]/',$column,2,PREG_SPLIT_NO_EMPTY);
        foreach( $a AS $one )
        {
            if ( $null || @$one[ $column ] )
                $ret[] = @$one[ $column ].($anc?'-'.@$one[$anc]:'');
        }
        return $ret;
    }

    /* support 2-level now */
    public static function AssColumn($a=array(), $column='id')
    {
        $two_level = func_num_args() > 2 ? true : false;
        if ( $two_level ) $scolumn = func_get_arg(2);

        $ret = array(); settype($a, 'array');
        if ( false == $two_level )
        {
            foreach( $a AS $one )
            {
                if ( is_array($one) )
                    $ret[ @$one[$column] ] = $one;
                else
                    $ret[ @$one->$column ] = $one;
            }
        }
        else
        {
            foreach( $a AS $one )
            {
                if (is_array($one)) {
                    if ( false==isset( $ret[ @$one[$column] ] ) ) {
                        $ret[ @$one[$column] ] = array();
                    }
                    $ret[ @$one[$column] ][ @$one[$scolumn] ] = $one;
                } else {
                    if ( false==isset( $ret[ @$one->$column ] ) )
                        $ret[ @$one->$column ] = array();

                    $ret[ @$one->$column ][ @$one->$scolumn ] = $one;
                }
            }
        }
        return $ret;
    }

    public static function GetRemoteIp($default='127.0.0.1')
    {
        $ip_string = $_SERVER['HTTP_CLIENT_IP'].','.$_SERVER['HTTP_X_FORWARDED_FOR'].','.$_SERVER['REMOTE_ADDR'];
        if ( preg_match ("/\d+\.\d+\.\d+\.\d+/", $ip_string, $matches) )
        {
            return $matches[0];
        }
        return $default;
    }

    public static function CombineNull($keys=array())
    {
        $ret = array();
        foreach( $keys AS $one )
        {
            $ret[$one] = null;
        }
        return $ret;
    }

    public static function ExtraEncode($extra=array())
    {
        return base64_encode(json_encode($extra));
    }

    public static function ExtraDecode($extra=null)
    {
        return json_decode(base64_decode($extra), true);
    }

    public static function GetPageNo($page='page')
    {
        $page_no = isset($_GET['page'])
            ?  abs(intval($_GET['page'])) : 1;
        return $page_no > 0 ? $page_no : 1;
    }

    public static function ScanDir($d, $e=array(), $r=false)
    {
        $c = scandir($d);
        $a = array();
        foreach($c as $o) {
            if ($o == '.' || $o == '..')
                continue;
            $p = $d . '/' . $o;
            $eo = substr($o, strrpos($o, '.') + 1);
            if ( ( empty($e) || in_array($eo, $e))
                    && is_file($p)
                    && is_readable($p))
            {
                $a[] = $p;
            }
            else if( is_dir($p)
                    && is_readable($p)
                    && true==$r)
            {
                $u = self::ScanDir($p, $e, $r);
            }
        }
        return $a;
    }

    public static function HumanTime($time=null, $forceDate=false)
    {
        $now = time();
        $time = is_numeric($time) ? $time : strtotime($time);

        $interval = $now - $time;

        if ( $forceDate || $interval > 30*86400 ){
            return strftime("%Y-%m-%d %a %H:%M",$time);
        }else if ( $interval > 86400 ){
            $number = intval($interval/86400);
            return "${number} Dias atrás";
        }else if ( $interval > 3600 ){ // > 1 hour
            $number = intval($interval/3600);
            return "${number} Horas atrás";
        }else if ( $interval >= 60 ){ // > 1 min
            $number = intval($interval/60);
            return "${number} Minutos atrás";
        }else if ( 5 >= $interval){// < 5 second
            return "Just now";
        }else{ // < 1 min
            return "${interval}Seconds before";
        }
    }

   public static function GetDate() {
        $w = array('S','M','T','W','T','F','S');
        return $now = array(
                'date' => date('m.d.Y'),
                'week' => 'week '.$w[ date('w')]
                );
    }

    public static function GetMessagePic($message) {
        $pic = '';
        $p = "#([^\s]{25,105})\.(jpg|gif|png)#i";
        if ( preg_match($p, $message, $mathes) ) {
            $pic = "{$mathes[1]}.{$mathes[2]}";
            $pic_s = explode('=', $pic);
            $pic = count($pic_s)>1 ? $pic_s[1]:$pic;
            $pic = trim(trim($pic),'\'"');
        }
        return $pic;
    }

    public static function CommaSplit($s=null, $n=-1) {
        return preg_split('/[\s,]+/',$s,$n,PREG_SPLIT_NO_EMPTY);
    }

    public static function CommaJoin($a=array(), $n=-1) {
        return join(',', $a);
    }

    public static function Getcsv($s, $split=' ') {
        $r = array();
        while( $s ) {
            $qp1 = mb_strpos($s, '"');
            $p0 = mb_strpos($s, $split);
            $p1 = mb_strpos($s, "\t");
            $p = false;

            if ( $p0!==FALSE && $p1 !==FALSE ) {
                $p = min($p0, $p1);
            } else if ( $p0 !== FALSE ) {
                $p = $p0;
            } else if ( $p1 !== FALSE ) {
                $p = $p1;
            }

            $qp2 = false;
            if ( $qp1===0 ) {
                $qp2 = mb_strpos($s, '"', 1);
            }

            if ( $qp2 !== FALSE ) {
                $m = mb_substr($s, 0, $qp2);
                $m = trim($m, '"');
                $s = trim(trim(mb_substr($s, $qp2+1)),"\t");
            } else if ($p !== FALSE ){
                $m = mb_substr($s, 0, $p);
                $s = trim(mb_substr($s, $p+1));
            } else {
                $m = $s;
                $s = null;
            }
            $r[] = $m;
        }
        return $r;
    }

    public static function GetKeyValue($string=null)
    {
        $csv = Utility::GetCsv($string);
        $kv = array();
        foreach( $csv AS $one ) {
            @list($name, $v) = explode('=', $one, 2);
            if ( $v === null ) $v = true;
            $kv[ strtolower($name) ] = trim($v,'" ');
        }
        return $kv;
    }

    static function GenSecret($len=6, $type=self::CHAR_WORD)
    {
        $secret = '';
        for ($i = 0; $i < $len;  $i++) {
            if ( self::CHAR_NUM==$type ){
				if (0==$i) {
					$secret .= chr(rand(49, 57));
				} else {
					$secret .= chr(rand(48, 57));
				}
            }else if ( self::CHAR_WORD==$type ){
                $secret .= chr(rand(65, 90));
            }else{
                if ( 0==$i ){
                    $secret .= chr(rand(65, 90));
                } else {
                    $secret .= (0==rand(0,1))?chr(rand(65, 90)):chr(rand(48,57));
                }
            }
        }
        return $secret;
    }
	
	public static function CreateGUID($namespace = '') {     
		static $guid = '';
		$uid = uniqid("", true);
		$data = $namespace;
		$data .= $_SERVER['REQUEST_TIME'];
		$data .= $_SERVER['HTTP_USER_AGENT'];
		$data .= $_SERVER['LOCAL_ADDR'];
		$data .= $_SERVER['LOCAL_PORT'];
		$data .= $_SERVER['REMOTE_ADDR'];
		$data .= $_SERVER['REMOTE_PORT'];
		$hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
		$guid = substr($hash,  0,  8) . 
				'-' .
				substr($hash,  8,  4) .
				'-' .
				substr($hash, 12,  4) .
				'-' .
				substr($hash, 16,  4) .
				'-' .
				substr($hash, 20, 12);
		return $guid;
	}

    static function Random($a=array()) {
        $tv = 0;
        foreach($a as $k=>$v) {
            if ($v<0) { $a[$k] = $v = 0; }
            $tv += $v;
        }
        if ( $tv == 0 ) return 0;
        $im = (float) 10000/$tv;
        $r = rand(0,10000);
        $tv = 0;
        foreach($a AS $k=>$v) {
            $tv += ($v*$im);
            if ( ceil($tv) >= $r )
                return $k;
        }
        return $k;
    }

	static function Redirect($u=null) {
		if (!$u) $u = $_SERVER['HTTP_REFERER'];
		if (!$u) $u = '/';
		Header("Location: {$u}");
		exit;
	}

	private static function GetHttpContent($fsock=null) {
		$out = null;
		while($buff = @fgets($fsock, 2048)){
			$out .= $buff;
		}
		fclose($fsock);
		$pos = strpos($out, "\r\n\r\n");
		$head = substr($out, 0, $pos);    //http head
		$status = substr($head, 0, strpos($head, "\r\n"));    //http status line
		$body = substr($out, $pos + 4, strlen($out) - ($pos + 4));//page body
		if(preg_match("/^HTTP\/\d\.\d\s([\d]+)\s.*$/", $status, $matches)){
			if(intval($matches[1]) / 100 == 2){
				return $body;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public static function DoGet($url){
		$url2 = parse_url($url);
		$url2["path"] = ($url2["path"] == "" ? "/" : $url2["path"]);
		$url2["port"] = ($url2["port"] == "" ? 80 : $url2["port"]);
		$host_ip = @gethostbyname($url2["host"]);
		$fsock_timeout = 2;  //2 second
		if(($fsock = fsockopen($host_ip, $url2['port'], $errno, $errstr, $fsock_timeout)) < 0){
			return false;
		}
		$request =  $url2["path"] .($url2["query"] ? "?".$url2["query"] : "");
		$in  = "GET " . $request . " HTTP/1.0\r\n";
		$in .= "Accept: */*\r\n";
		$in .= "User-Agent: Payb-Agent\r\n";
		$in .= "Host: " . $url2["host"] . "\r\n";
		$in .= "Connection: Close\r\n\r\n";
		if(!@fwrite($fsock, $in, strlen($in))){
			fclose($fsock);
			return false;
		}
		return self::GetHttpContent($fsock);
	}

	public static function DoPost($url,$post_data=array()){
		$url2 = parse_url($url);
		$url2["path"] = ($url2["path"] == "" ? "/" : $url2["path"]);
		$url2["port"] = ($url2["port"] == "" ? 80 : $url2["port"]);
		$host_ip = @gethostbyname($url2["host"]);
		$fsock_timeout = 2; //2 second
		if(($fsock = fsockopen($host_ip, $url2['port'], $errno, $errstr, $fsock_timeout)) < 0){
			return false;
		}
		$request =  $url2["path"].($url2["query"] ? "?" . $url2["query"] : "");
		$post_data2 = http_build_query($post_data);
		$in  = "POST " . $request . " HTTP/1.0\r\n";
		$in .= "Accept: */*\r\n";
		$in .= "Host: " . $url2["host"] . "\r\n";
		$in .= "User-Agent: Lowell-Agent\r\n";
		$in .= "Content-type: application/x-www-form-urlencoded\r\n";
		$in .= "Content-Length: " . strlen($post_data2) . "\r\n";
		$in .= "Connection: Close\r\n\r\n";
		$in .= $post_data2 . "\r\n\r\n";
		unset($post_data2);
		if(!@fwrite($fsock, $in, strlen($in))){
			fclose($fsock);
			return false;
		}
		return self::GetHttpContent($fsock);
	}

	static function HttpRequest($url, $data=array(), $abort=false) {
		if ( !function_exists('curl_init') ) { return empty($data) ? self::DoGet($url) : self::DoPost($url, $data); }
		$timeout = $abort ? 1 : 2;
		$ch = curl_init();
		if (is_array($data) && $data) {
			$formdata = http_build_query($data);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $formdata);
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		$result = curl_exec($ch);
		return (false===$result && false==$abort)? ( empty($data) ? self:: DoGet($url) : self::DoPost($url, $data) ) : $result;
	}
	
	static function MakeURL($str) {
		return strtolower(preg_replace(array('/[^a-zA-Z0-9_ -]/', '/[ ]+/', '/^-|-$/'), array('', '_', ''), self::RemoveAccent($str)));
	}

	static function RemoveAccent($str) {
		return iconv('UTF-8', 'ASCII//TRANSLIT', str_replace(array('ª', 'º', ','), array('', '', '-'), self::StripAccents($str)));
	}
	
	static function StripAccents($str){
		return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
	}
	
}
