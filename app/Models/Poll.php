<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-26
 * Time: 15:51
 */

namespace App\Models;

use App\Traits\SanitizedRequest;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use SanitizedRequest;

    static function buildData($all, $ansid) {

        $data = array ();
        $alldata = array ();

        if( $all != "" ) {
            $all = explode( "|", $all );

            foreach ( $all as $vote ) {
                list ( $answerid, $answervalue ) = explode( ":", $vote );
                $data[$answerid] = intval( $answervalue );
            }
        }

        foreach ( $ansid as $id ) {
            if(isset($data[$id])) {
                $data[$id] ++;
            } else {
                $data[$id] = 1;
            }
        }

        foreach ( $data as $key => $value ) {
            $alldata[] = intval( $key ) . ":" . intval( $value );
        }

        $alldata = implode( "|", $alldata );

        return $alldata;
    }

    static function getVotes($all){
        $data = array ();

        if( $all != "" ) {
            $all = explode( "|", $all );

            foreach ( $all as $vote ) {
                list ( $answerid, $answervalue ) = explode( ":", $vote );
                $data[$answerid] = intval( $answervalue );
            }
        }

        return $data;
    }

    static public function buildResult($poll) {
        $body = explode( "\n", stripslashes( $poll->body ) );
        $answer = Poll::getVotes( $poll->answer );
        $allcount = intval($poll->votes);
        $pn = 0;
        $result = array();

        for($v = 0; $v < sizeof( $body ); $v ++) {
            if(isset($answer[$v])) {
                $num = $answer[$v];
            } else {
                $num = 0;
            }

            ++ $pn;
            if( $pn > 5 ) $pn = 1;

            if( $allcount != 0 ) $proc = (100 * $num) / $allcount;
            else $proc = 0;

            $proc = round( $proc, 2 );
            $vote = new \stdClass();
            $vote->title = $body[$v];
            $vote->num = $num;
            $vote->proc = $proc;
            $vote->procIntval = intval($proc);
            $vote->color = sprintf('#%06X', mt_rand(0x775999, 0xFFFF00));
            $result[] = $vote;
        }

        return $result;
    }
}