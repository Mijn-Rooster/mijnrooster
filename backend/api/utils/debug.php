<?php

function networkRequestDebugInfo($curl, $json = false)
{
    $version = curl_version();
    extract(curl_getinfo($curl));
    if ($json) {
        $debugInfo = json_encode([
            'url' => $url,
            'code' => $http_code,
            'redirect_count' => $redirect_count,
            'redirect_time' => $redirect_time,
            'content_type' => $content_type,
            'download_content_length' => $download_content_length,
            'size_download' => $size_download,
            'filetime' => $filetime,
            'total_time' => $total_time,
            'starttransfer_time' => $starttransfer_time,
            'namelookup_time' => $namelookup_time,
            'connect_time' => $connect_time,
            'pretransfer_time' => $pretransfer_time,
            'speed_download' => $speed_download,
            'speed_upload' => $speed_upload,
            'version' => $version['version']
        ]);
    } else {


        $debugInfo = <<<EOD
URL....: $url
Code...: $http_code ($redirect_count redirect(s) in $redirect_time secs)
Content: $content_type Size: $download_content_length (Own: $size_download) Filetime: $filetime
Time...: $total_time Start @ $starttransfer_time (DNS: $namelookup_time Connect: $connect_time Request: $pretransfer_time)
Speed..: Down: $speed_download (avg.) Up: $speed_upload (avg.)
Curl...: v{$version['version']}
EOD;
    }

    return $debugInfo;
}
