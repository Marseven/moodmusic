<?php
/**
 * Created by NiNaCoder.
 * Date: 2012-05-1
 * Time: 12:11
 */

namespace App\Models;

use Cache;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Download {

    var $properties = array ('media' => "", 'type' => "", 'size' => "", 'resume' => "", 'max_speed' => "" );

    var $range = 0;

    function __construct($media, $name = "", $resume = 0, $max_speed = 0) {
        ob_start();

        $this->properties = array ('media' => $media, 'name' => $name, 'type' => "application/force-download", 'size' => $media->size, 'resume' => $resume, 'max_speed' => $max_speed );
        $this->properties['type'] = $this->properties['media']->mime_type;

        if( $this->properties['resume'] ) {

            if( isset( $_SERVER['HTTP_RANGE'] ) ) {
                $this->range = $_SERVER['HTTP_RANGE'];
                $this->range = str_replace( "bytes=", "", $this->range );
                $this->range = str_replace( "-", "", $this->range );
            } else {
                $this->range = 0;
            }

            if( $this->range > $this->properties['size'] ) {
                $this->range = 0;
            }

        } else {
            $this->range = 0;
        }
    }

    function downloadFile() {
        if( $this->range ) {
            header( $_SERVER['SERVER_PROTOCOL'] . " 206 Partial Content" );
        } else {
            header( $_SERVER['SERVER_PROTOCOL'] . " 200 OK" );
        }

        header( "Pragma: public" );
        header( "Expires: 0" );
        header( "Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header( "Cache-Control: private", false);
        header( "Content-Type: " . $this->properties['type'] );
        header( 'Content-Disposition: attachment; filename="' . $this->properties['name'] . '"' );
        header( "Content-Transfer-Encoding: binary" );

        if($this->properties['max_speed'] === 0) {
            if(config('filesystems.disks')[$this->properties['media']->disk]['driver'] == 'local') {
                readfile($this->properties['media']->getPath());
            } else {
                readfile($this->properties['media']->getTemporaryUrl(Carbon::now()->addMinutes(intval(config('settings.s3_signed_time', 5)))));
            }
        } else {
            if( $this->properties['resume'] ) {
                header( "Accept-Ranges: bytes" );
            }

            if( $this->range ) {
                header( "Content-Range: bytes {$this->range}-" . ($this->properties['size'] - 1) . "/" . $this->properties['size'] );
                header( "Content-Length: " . ($this->properties['size'] - $this->range) );
            } else {
                header( "Content-Length: " . $this->properties['size'] );
            }

            header("Connection: close");

            @ini_set( 'max_execution_time', 0 );
            @set_time_limit();

            if(config('filesystems.disks')[$this->properties['media']->disk]['driver'] == 'local') {
                $this->downloadThrottle( $this->properties['media']->getPath(), $this->range );
            } else {
                $filePath = storage_path('app/public/' . Str::random(32));
                $this->downloadUrlToFile($this->properties['media']->getUrl(), $filePath);
                $this->downloadThrottle($filePath, $this->range, true);
            }
        }
    }

    function downloadThrottle($filePath, $range = 0, $shouldRemoveFile = false) {
        @ob_end_clean();

        if( ($speed = $this->properties['max_speed']) > 0 ) {
            $sleep_time = (8 / $speed) * 1e6;
        } else {
            $sleep_time = 0;
        }

        $handle = fopen( $filePath, 'rb' );
        fseek( $handle, $range );

        if( $handle === false ) {
            return false;
        }

        while ( !feof( $handle ) ) {
            print( fread( $handle, 8192 ) );
            ob_flush();
            flush();

            if( $sleep_time ) {
                usleep( $sleep_time );
            }
        }

        fclose( $handle );

        if($shouldRemoveFile) {
            @unlink($filePath);
        }
        return true;
    }

    function downloadUrlToFile($url, $outFileName)
    {
        if(is_file($url)) {
            copy($url, $outFileName);
        } else {
            $options = array(
                CURLOPT_FILE    => fopen($outFileName, 'w'),
                CURLOPT_TIMEOUT =>  28800, // set this to 8 hours so we dont timeout on big files
                CURLOPT_URL     => $url
            );

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            curl_exec($ch);
            curl_close($ch);
        }
    }
}