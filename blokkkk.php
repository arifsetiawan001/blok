<?php

$secret = '83415d06-ec4e-11e6-a41b-6c40088ab51e';
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'X-AppVersion: 4.33.1';
$headers[] = "X-Uniqueid: ac94e5d0e7f3f".rand(111,999);
$headers[] = 'X-Location: -6.405821,106.064193';

        echo "\n";
        echo "\e[92m==============================================\n";
        echo "\e[92m=                   Rama4rt                  =\n";
        echo "\e[92m=        Auto Register + Redeem Gojek        =\n";
        echo "\e[92m==============================================\n";
        echo "\n";
        ulang:
        echo "\e[96m[+] Enter Phone Number : ";
        $number = trim(fgets(STDIN));
        $numbers = $number[0].$number[1];
        $numberx = $number[5];
        if($numbers == "08") { 
            $number = str_replace("08","628",$number);
        } elseif ($numberx == " ") {
            $number = preg_replace("/[^0-9]/", "",$number);
            $number = "1".$number;
        }
        $nama = nama();
        $email = strtolower(str_replace(" ", "", $nama) . mt_rand(100,999) . "@gmail.com");
        $data1 = '{"name":"' . $nama . '","email":"' . $email . '","phone":"+' . $number . '","signed_up_country":"ID"}';
        $reg = curl('https://api.gojekapi.com/v5/customers', $data1, $headers);
        $regs = json_decode($reg[0]);
        // Verif OTP
        if($regs->success == true) {
            otp:
            echo "\e[93m[+] Input OTP : ";
            $otp = trim(fgets(STDIN));
            $data2 = '{"client_name":"gojek:cons:android","data":{"otp":"' . $otp . '","otp_token":"' . $regs->data->otp_token . '"},"client_secret":"' . $secret . '"}';
            $verif = curl('https://api.gojekapi.com/v5/customers/phone/verify', $data2, $headers);
            $verifs = json_decode($verif[0]);
            if($verifs->success == true) {
                // Claim Voucher
                $token = $verifs->data->access_token;
                $headers[] = 'Authorization: Bearer '.$token;
                $live = "tokens";
                $fopen1 = fopen($live, "a+");
                $fwrite1 = fwrite($fopen1, "TOKEN => ".$token." \n NOMOR => ".$number." \n");
                fclose($fopen1);
                echo "Token ~> ".$token." \n";
                echo "\e[92m[+] File Token Saved in ~> ".$live." \n\n";
                
                // SANTAI19
                echo "\e[96m[!] Redeem Voucher Gofood : BERKAHGOJEK\n";
                $data3 = '{"promo_code":"BERKAHGOJEK"}';
                $claim = curl('https://api.gojekapi.com/go-promotions/v1/promotions/enrollments', $data3, $headers);
                $claims = json_decode($claim[0]); 
                if($claims->success == true) 
                        {
                                // Claim Voucher
                                $live2 = "berkahgojek";
                                $fopen2 = fopen($live2, "a+");
                                $fwrite2 = fwrite($fopen2, "TOKEN => ".$token." \n");
                                fclose($fopen2);
                                echo "\e[92m [✓]".$claims->data->message;
                        } 
                        else 
                            {
                                echo "\e[91m [×] Failed to Claim Voucher!";
                                    sleep(5);
                                    echo "\n";

                            }

                sleep(5);
                echo "\n";
                    
                $cekvoucher = request('/gopoints/v3/wallet/vouchers?limit=10&page=1', $token);
                $total = fetch_value($cekvoucher,'"total_vouchers":',',');
                $voucher3 = getStr1('"title":"','",',$cekvoucher,"3");
                $voucher1 = getStr1('"title":"','",',$cekvoucher,"1");
                $voucher2 = getStr1('"title":"','",',$cekvoucher,"2");
                $voucher4 = getStr1('"title":"','",',$cekvoucher,"4");
                $voucher5 = getStr1('"title":"','",',$cekvoucher,"5");
                $voucher6 = getStr1('"title":"','",',$cekvoucher,"6");
                $voucher7 = getStr1('"title":"','",',$cekvoucher,"7");
                
                
                $expired1 = getStr1('"expiry_date":"','"',$cekvoucher,'1');
                $expired2 = getStr1('"expiry_date":"','"',$cekvoucher,'2');
                $expired3 = getStr1('"expiry_date":"','"',$cekvoucher,'3');
                $expired4 = getStr1('"expiry_date":"','"',$cekvoucher,'4');
                $expired5 = getStr1('"expiry_date":"','"',$cekvoucher,'5');
                $expired6 = getStr1('"expiry_date":"','"',$cekvoucher,'6');
                $expired7 = getStr1('"expiry_date":"','"',$cekvoucher,'7');
                    
                
                echo "\n""!] Total Voucher ".$total." : ");
                echo "\n""1] ".$voucher1);
                echo "\n"" EXP ~> ".$expired1);
                echo "\n""2] ".$voucher2);
                echo "\n"" EXP ~> ".$expired2);
                echo "\n""3] ".$voucher3);
                echo "\n"" EXP ~> ".$expired3);
                echo "\n""4] ".$voucher4);
                echo "\n"" EXP ~> ".$expired4);
                echo "\n""5] ".$voucher5);
                echo "\n"" EXP ~> ".$expired5);
                echo "\n""6] ".$voucher6);
                echo "\n"" EXP ~> ".$expired6);
                echo "\n""7] ".$voucher7);
                echo "\n"" EXP ~> ".$expired7);
                echo"\n";

}else
    {
        echo color("red","[×] Your OTP is Wrong");
        echo"\n==================================\n\n";
        echo color("yellow","[!] Input Your OTP Again!\n");
        goto otp;
    }
                    
                } else
                    {
                        echo color("red","[×] Your Number Has Been Register!");
                        echo"\n==================================\n\n";
                        echo color("yellow","[!] Please Register Again!\n");
                        goto ulang;
                    }   




function request($url, $token = null, $data = null, $pin = null, $otpsetpin = null, $uuid = null)
    {

    $header[] = "Host: api.gojekapi.com";
    $header[] = "User-Agent: okhttp/3.10.0";
    $header[] = "Accept: application/json";
    $header[] = "Accept-Language: id-ID";
    $header[] = "Content-Type: application/json; charset=UTF-8";
    $header[] = "X-AppVersion: 4.33.1";
    $header[] = "X-UniqueId: ".time()."57".mt_rand(1000,9999);
    $header[] = "Connection: keep-alive";
    $header[] = "X-User-Locale: id_ID";
    $header[] = "X-Location: -6.917464,107.619122";
    $header[] = "X-Location-Accuracy: 3.0";
    if ($pin):
    $header[] = "pin: $pin";
        endif;
    if ($token):
    $header[] = "Authorization: Bearer $token";
    endif;
    if ($otpsetpin):
    $header[] = "otp: $otpsetpin";
    endif;
    if ($uuid):
    $header[] = "User-uuid: $uuid";
    endif;
    $c = curl_init("https://api.gojekapi.com".$url);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        if ($data):
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_POST, true);
        endif;
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HEADER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, $header);
        $response = curl_exec($c);
        $httpcode = curl_getinfo($c);
        if (!$httpcode)
            return false;
        else {
            $header = substr($response, 0, curl_getinfo($c, CURLINFO_HEADER_SIZE));
            $body   = substr($response, curl_getinfo($c, CURLINFO_HEADER_SIZE));
        }
        $json = json_decode($body, true);
        return $body;
    }

function getStr($a,$b,$c)
    {
        $a = @explode($a,$c)[1];
        return @explode($b,$a)[0];
    }

function getStr1($a,$b,$c,$d)
    {
            $a = @explode($a,$c)[$d];
            return @explode($b,$a)[0];
    }

function fetch_value($str,$find_start,$find_end)
    {
        $start = @strpos($str,$find_start);
        if ($start === false) {
            return "";
        }
        $length = strlen($find_start);
        $end    = strpos(substr($str,$start +$length),$find_end);
        return trim(substr($str,$start +$length,$end));
    }

function nama()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://ninjaname.horseridersupply.com/indonesian_name.php");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $ex = curl_exec($ch);
        // $rand = json_decode($rnd_get, true);
        preg_match_all('~(&bull; (.*?)<br/>&bull; )~', $ex, $name);
        return $name[2][mt_rand(0, 14) ];
    }

function curl($url, $fields = null, $headers = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($fields !== null) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        if ($headers !== null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $result   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return array(
            $result,
            $httpcode
        );
    }
