<?php

use Illuminate\Database\Seeder;

class EmailsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('emails')->delete();
        
        \DB::table('emails')->insert(array (
            0 => 
            array (
                'id' => 1,
                'type' => 'resetPassword',
                'description' => 'Configure e-mail message that is sent to recover the forgotten password',
                'subject' => 'Reset your password',
                'content' => '<p>Dear {{name}},</p>
<p>Please <a href="{{resetLink}}">click here</a> to reset your password.</p>
<p>If you are not able to click on link please copy and paste: {{resetLink}} in your browser.</p>
<p>Regards,</p>
<p>Team NiNaCoder.</p>',
                'created_at' => NULL,
                'updated_at' => '2020-07-02 22:35:53',
            ),
            1 => 
            array (
                'id' => 2,
                'type' => 'verifyAccount',
                'description' => 'Configure e-mail message that is sent to activate your account',
                'subject' => '{{name}}! Verify your account',
                'content' => '<div>Dear {{name}},</div>
<div>&nbsp;</div>
<div>You&rsquo;re almost there. Confirm your account below to finish creating your account.</div>
<div>Please&nbsp;<a href="{{validationLink}}" data-name="Apple Music Toolbox" data-type="url">click here</a>&nbsp;to confirm your account.</div>
<div>&nbsp;</div>
<div>If you are not able to click on link please copy and paste: {{validationLink}} in your browser.</div>
<div>&nbsp;</div>
<div>Regards,</div>
<div>Team Music Engine.</div>',
                'created_at' => NULL,
                'updated_at' => '2020-09-07 07:27:20',
            ),
            2 => 
            array (
                'id' => 3,
                'type' => 'newUser',
                'description' => 'Configure e-mail message that is sent to welcome new user',
                'subject' => 'Welcome, We\'re so Glad You\'re Here',
                'content' => '<p>{{name}}, Welcome to NiNa Sound</p>
<p>Play and discover music you love on your phone for FREE.</p>
<p>Regards,</p>
<p>Team NiNaCoder.</p>',
                'created_at' => NULL,
                'updated_at' => '2019-08-24 03:03:33',
            ),
            3 => 
            array (
                'id' => 4,
                'type' => 'approvedArtist',
                'description' => 'Configure e-mail message that is sent to welcome new artist',
                'subject' => 'Your claiming to access artist has been approved!',
                'content' => '<p>Your claiming to access artist has been approved!</p>
<p>Hi {{name}},</p>
<p>Congratulations! Your claming to access artist "{{artist_name}}" has been approved.</p>
<p>To start uploading songs/albums, get logging in to your account you will see a new menu Aritst/Band Management right on your account profile.</p>
<p>Regards,</p>
<p>Music Engine Team</p>',
                'created_at' => NULL,
                'updated_at' => '2020-09-07 07:23:07',
            ),
            4 => 
            array (
                'id' => 5,
                'type' => 'rejectedArtist',
                'description' => 'Configure e-mail message that is sent when an artist claim request has been rejected',
                'subject' => 'You claiming request has been rejected',
                'content' => '<p>Hi, {{name}}</p>
<p>&nbsp;</p>
<p>Thank you for your submission. We have completed our review of your claming for accessing the artist "{{artist_name}}".</p>
<p>After carefully review we are deceived to not accept your request&nbsp; and you won\'t be able to re-claim this artist again.</p>
<p>{{comment}}</p>
<p>Thank for every!</p>
<p>All the best!</p>
<p>Music Engine team</p>',
                'created_at' => NULL,
                'updated_at' => '2020-09-07 07:20:24',
            ),
            5 => 
            array (
                'id' => 6,
                'type' => 'subscribePlaylist',
                'description' => 'Configure e-mail message that when some one subscribe a playlist',
                'subject' => '{{friendName}} just subscribed your playlist',
                'content' => '<p>Hi {{name}}</p>
<p>{{friendName}} just subscribed your playlist.</p>',
                'created_at' => NULL,
                'updated_at' => '2020-09-07 07:24:23',
            ),
            6 => 
            array (
                'id' => 7,
                'type' => 'shareMedia',
                'description' => 'Configure e-mail message that when some one share media',
                'subject' => '{{friendName}} just shared something with you.',
                'content' => '<p>H, {{name}}</p>
<p>{{friendName}} just shared something with you.</p>',
                'created_at' => NULL,
                'updated_at' => '2020-09-07 07:25:06',
            ),
            7 => 
            array (
                'id' => 8,
                'type' => 'followUser',
                'description' => 'Configure e-mail message that when people following each other',
                'subject' => '{{friendName}} is now following you',
                'content' => '<p>Hi {{name}}</p>
<p>{{friendName}} is now following you</p>',
                'created_at' => NULL,
                'updated_at' => '2020-09-07 07:25:34',
            ),
            8 => 
            array (
                'id' => 9,
                'type' => 'newComment',
                'description' => 'Configure e-mail message that is sent when a new comment is posted on the site',
                'subject' => 'New comment has been posted',
                'content' => '<p>Dear Admin,</p>
<p>The comment for the article that you have subscribed to was added on your site</p>
<p>------------------------------------------------</p>
<p><strong>Summary of the comment</strong></p>
<p>------------------------------------------------</p>
<p>Author: {{name}}</p>
<p>Author username: {{username}}</p>
<p>Date: {{created_at}}</p>
<p>Link to the object: <a href="{{url}}" target="_blank" rel="noopener">{{url<span style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;">}}</span></a></p>
<p>------------------------------------------------</p>
<p><strong>Comment text</strong></p>
<p>------------------------------------------------</p>
<p>{{text}}</p>
<p>------------------------------------------------</p>
<p>&nbsp;</p>
<p>NiNCoder Team</p>',
                'created_at' => '2019-08-20 17:00:00',
                'updated_at' => '2020-07-15 23:28:31',
            ),
            9 => 
            array (
                'id' => 10,
                'type' => 'approvedSong',
                'description' => 'Configure e-mail message that is sent when a song has been approved',
                'subject' => 'Your song, {{title}}, has been approved',
                'content' => '<p>Your song has been approved!</p>
<p>Hi {{name}},</p>
<p>Congratulations! Your song "{{title}}" has been approved. You can view it online here:</p>
<p><a href="{{url}}" target="_blank" rel="noopener">{{url}}</a></p>
<p>Thanks for your high quality submission. Keep up the awesome work!</p>
<p>Regards,</p>
<p>NiNaCoder Team</p>',
                'created_at' => NULL,
                'updated_at' => '2020-07-15 03:01:27',
            ),
            10 => 
            array (
                'id' => 11,
                'type' => 'approvedAlbum',
                'description' => 'Configure e-mail message that is sent when an album has been approved',
                'subject' => 'Your album, {{title}}, has been approved!',
                'content' => '<p>Your album has been approved!</p>
<p>Hi {{name}},</p>
<p>Congratulations! Your album&nbsp;"{{title}}" has been approved. You can view it online here:</p>
<p><a href="{{url}}" target="_blank" rel="noopener">{{url}}</a></p>
<p>Thanks for your high quality submission. Keep up the awesome work!</p>
<p>Regards,</p>
<p>NiNaCoder Team</p>',
                'created_at' => NULL,
                'updated_at' => '2020-07-15 03:01:13',
            ),
            11 => 
            array (
                'id' => 12,
                'type' => 'rejectedSong',
                'description' => 'Configure e-mail message that is sent when a song has been rejected',
                'subject' => 'Your song, {{title}}, has been rejected',
                'content' => '<p>Hi, {{name}}</p>
<p>&nbsp;</p>
<p>Thank you for your submission. We have completed our review of "{{title}}," and unfortunately we found it isn\'t at the quality standard required to move forward, and you won\'t be able to re-submit this song again.</p>
<p>{{comment}}</p>
<p>We appreciate the effort and time you\'ve put into creating your song. And we\'d be happy to help make sure your next entry will meet our submission requirements. Here\'s our advice:</p>
<p>Visit our forums and ask fellow authors for feedback. Our helpful community will be glad to lend a hand.</p>
<p>Check out this Help Centre article to understand why and how items get rejected.</p>
<p>We hope to see a new submission from you soon!</p>
<p>All the best!</p>
<p>NiNaCoder team</p>',
                'created_at' => NULL,
                'updated_at' => '2020-07-15 02:58:15',
            ),
            12 => 
            array (
                'id' => 13,
                'type' => 'rejectedAlbum',
                'description' => 'Configure e-mail message that is sent when an album has been rejected',
                'subject' => 'Your album, {{title}}, has been rejected',
                'content' => '<p>Hi, {{name}}</p>
<p>&nbsp;</p>
<p>Thank you for your submission. We have completed our review of "{{title}}," and unfortunately we found it isn\'t at the quality standard required to move forward, and you won\'t be able to re-submit this album again.</p>
<p>{{comment}}</p>
<p>We appreciate the effort and time you\'ve put into creating your song. And we\'d be happy to help make sure your next entry will meet our submission requirements. Here\'s our advice:</p>
<p>Visit our forums and ask fellow authors for feedback. Our helpful community will be glad to lend a hand.</p>
<p>Check out this Help Centre article to understand why and how items get rejected.</p>
<p>We hope to see a new submission from you soon!</p>
<p>All the best!</p>
<p>NiNaCoder team</p>',
                'created_at' => NULL,
                'updated_at' => '2020-07-15 02:58:00',
            ),
            13 => 
            array (
                'id' => 14,
                'type' => 'subscriptionReceipt',
                'description' => 'Configure e-mail message that is sent when a subscription has been placed.',
                'subject' => 'Your receipt from ‪‬ #{{receipt_id}}',
                'content' => '<div style="margin: 0; padding: 0; border: 0; background-color: #f1f5f9; font-family: -apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Ubuntu,sans-serif; min-width: 100%!important; width: 100%!important;">
<div style="display: none!important; max-height: 0; max-width: 0; overflow: hidden; color: #ffffff; font-size: 1px; line-height: 1px; opacity: 0;">&nbsp;</div>
<div style="min-width: 100%; width: 100%; background-color: #f1f5f9;">
<table class="m_-8504956366380948631Wrapper" style="border: 0; border-collapse: collapse; margin: 0 auto!important; padding: 0; max-width: 600px; min-width: 600px; width: 600px;" align="center">
<tbody>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0;">
<table class="m_-8504956366380948631Divider--kill" style="border: 0; border-collapse: collapse; margin: 0; padding: 0;" width="100%">
<tbody>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" height="20">&nbsp;</td>
</tr>
</tbody>
</table>
<div style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
<table class="m_-8504956366380948631Section m_-8504956366380948631Header" dir="ltr" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; background-color: #ffffff; width: 100%;" width="100%">
<tbody>
<tr>
<td class="m_-8504956366380948631Header-left m_-8504956366380948631Target" style="background-color: #e23136; border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; font-size: 30px; line-height: 156px; background-size: 100% 100%; border-top-left-radius: 5px; color: white; text-align: center;" align="right" valign="bottom" width="100%" height="156">Music Engine</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; background-color: #ffffff; height: 59px; width: 100%;" width="100%">
<tbody>
<tr style="height: 59px;">
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 59px;" width="64">&nbsp;</td>
<td class="m_-8504956366380948631Content" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; width: 472px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #32325d; font-size: 24px; line-height: 32px; height: 59px;" align="center">Receipt from</td>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 59px;" width="64">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; background-color: #ffffff;" width="100%">
<tbody>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" height="8">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; background-color: #ffffff;" width="100%">
<tbody>
<tr>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" width="64">&nbsp;</td>
<td class="m_-8504956366380948631Content" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; width: 472px; font-family: -apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Ubuntu,sans-serif; vertical-align: middle; color: #8898aa; font-size: 15px; line-height: 18px;" align="center"><span class="il">Invoice</span> #{{invoice_id}}</td>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" width="64">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; background-color: #ffffff;" width="100%">
<tbody>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" height="4">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; background-color: #ffffff; height: 18px; width: 100%;" width="100%">
<tbody>
<tr style="height: 18px;">
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 18px;" width="64">&nbsp;</td>
<td class="m_-8504956366380948631Content" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; width: 472px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #8898aa; font-size: 15px; line-height: 18px; height: 18px;" align="center">Receipt #{{receipt_id}}</td>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 18px;" width="64">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; background-color: #ffffff;" width="100%">
<tbody>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" height="24">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; background-color: #ffffff; width: 100%;" width="100%">
<tbody>
<tr>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" width="64">&nbsp;</td>
<td class="m_-8504956366380948631Content" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; width: 472px;">
<table style="border: 0; border-collapse: collapse; margin: 0; padding: 0; width: 100%;">
<tbody>
<tr>
<td class="m_-8504956366380948631DataBlocks-item" style="border: 0; border-collapse: collapse; margin: 0; padding: 0;" valign="top">
<table style="border: 0; border-collapse: collapse; margin: 0; padding: 0;">
<tbody>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; font-family: -apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Ubuntu,sans-serif; vertical-align: middle; color: #8898aa; font-size: 12px; line-height: 16px; white-space: nowrap; font-weight: bold; text-transform: uppercase;">Amount paid</td>
</tr>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; font-family: -apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Ubuntu,sans-serif; vertical-align: middle; color: #525f7f; font-size: 15px; line-height: 24px; white-space: nowrap;">{{currency}}{{amount}}</td>
</tr>
</tbody>
</table>
</td>
<td class="m_-8504956366380948631DataBlocks-spacer" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" width="20">&nbsp;</td>
<td class="m_-8504956366380948631DataBlocks-item" style="border: 0; border-collapse: collapse; margin: 0; padding: 0;" valign="top">
<table style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; width: 94px;">
<tbody>
<tr>
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #8898aa; font-size: 12px; line-height: 16px; white-space: nowrap; font-weight: bold; text-transform: uppercase; width: 94px;">Date paid</td>
</tr>
<tr>
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #525f7f; font-size: 15px; line-height: 24px; white-space: nowrap; width: 94px;">{{issued_date}}</td>
</tr>
</tbody>
</table>
</td>
<td class="m_-8504956366380948631DataBlocks-spacer" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" width="20">&nbsp;</td>
<td class="m_-8504956366380948631DataBlocks-item" style="border: 0; border-collapse: collapse; margin: 0; padding: 0;" valign="top">
<table style="border: 0; border-collapse: collapse; margin: 0; padding: 0;">
<tbody>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; font-family: -apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Ubuntu,sans-serif; vertical-align: middle; color: #8898aa; font-size: 12px; line-height: 16px; white-space: nowrap; font-weight: bold; text-transform: uppercase;">Payment method</td>
</tr>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; font-family: -apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Ubuntu,sans-serif; vertical-align: middle; color: #525f7f; font-size: 15px; line-height: 24px; white-space: nowrap;">{{payment_gate}}</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" width="64">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; background-color: #ffffff;" width="100%">
<tbody>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" height="32">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; background-color: #ffffff; height: 28px;">
<tbody>
<tr style="height: 16px;">
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 16px; width: 64px;">&nbsp;</td>
<td class="m_-8504956366380948631Content" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; width: 472px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #8898aa; font-size: 12px; line-height: 16px; font-weight: bold; text-transform: uppercase; height: 16px;">Summary</td>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 16px; width: 64px;">&nbsp;</td>
</tr>
<tr style="height: 12px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 12px; width: 600px;" colspan="3" height="12">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; background-color: #ffffff;" width="100%">
<tbody>
<tr>
<td class="m_-8504956366380948631Spacer--kill" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" width="64">&nbsp;</td>
<td class="m_-8504956366380948631Content" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; width: 472px;">
<table style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; width: 100%; background-color: #f6f9fc; border-radius: 4px; height: 345px;">
<tbody>
<tr style="height: 4px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 4px;" colspan="3" height="4">&nbsp;</td>
</tr>
<tr style="height: 331px;">
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 331px;" width="20">&nbsp;</td>
<td class="m_-8504956366380948631Table-content" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; width: 432px; height: 331px;">
<table class="m_-8504956366380948631Table-rows" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; height: 339px;" width="432">
<tbody>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 16px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #8898aa; font-size: 12px; line-height: 16px; font-weight: bold; text-transform: uppercase; height: 16px; width: 367px;">{{issued_date}} &ndash; {{next_billing}}</td>
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 16px; width: 8px;">&nbsp;</td>
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 16px; width: 57px;">&nbsp;</td>
</tr>
<tr style="height: 10px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 10px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 24px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #525f7f; font-size: 15px; line-height: 24px; word-break: break-word; height: 24px; width: 367px;">{{plan}} {{currency}}{{plan_price}} / {{plan_frequency}}&nbsp;<span class="m_-8504956366380948631Content" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; font-family: -apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Ubuntu,sans-serif; color: #8898aa; font-size: 14px; line-height: 14px;"> &times; 1</span></td>
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 24px; width: 8px;">&nbsp;</td>
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #525f7f; font-size: 15px; line-height: 24px; height: 24px; width: 57px;" align="right" valign="top">{{currency}}{{amount}}</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 10px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 10px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 1px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 1px; width: 432px;" colspan="3" bgcolor="e6ebf1" height="1">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 24px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #525f7f; font-size: 15px; line-height: 24px; font-weight: 500; height: 24px; width: 367px;">Subtotal</td>
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 24px; width: 8px;">&nbsp;</td>
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #525f7f; font-size: 15px; line-height: 24px; font-weight: 500; height: 24px; width: 57px;" align="right" valign="top">{{currency}}{{amount}}</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 24px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #525f7f; font-size: 15px; line-height: 24px; font-weight: bold; height: 24px; width: 367px;">Amount paid</td>
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 24px; width: 8px;">&nbsp;</td>
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #525f7f; font-size: 15px; line-height: 24px; font-weight: bold; height: 24px; width: 57px;" align="right" valign="top">{{currency}}{{amount}}</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 1px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 1px; width: 432px;" colspan="3" bgcolor="e6ebf1" height="1">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
<tr style="height: 6px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 6px; width: 432px;" colspan="3" height="6">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 331px;" width="20">&nbsp;</td>
</tr>
<tr style="height: 10px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 10px;" colspan="3" height="4">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
<td class="m_-8504956366380948631Spacer--kill" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" width="64">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; background-color: #ffffff;" width="100%">
<tbody>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" height="44">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; background-color: #ffffff;" width="100%">
<tbody>
<tr>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" width="64">&nbsp;</td>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" bgcolor="e6ebf1" height="1">&nbsp;</td>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" width="64">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; background-color: #ffffff; height: 32px; width: 100%;" width="100%">
<tbody>
<tr style="height: 32px;">
<td style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 32px;" height="32">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; background-color: #ffffff;" width="100%">
<tbody>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" height="20">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; background-color: #ffffff; height: 16px;">
<tbody>
<tr style="height: 16px;">
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 16px; width: 64px;">&nbsp;</td>
<td class="m_-8504956366380948631Content" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; width: 472px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; vertical-align: middle; color: #8898aa; font-size: 12px; line-height: 16px; height: 16px;">If you have any questions, please send an email to <a href="mailto:ninacoder2510@gmail.com">ninacoder2510@gmail.com</a>. We\'ll get back to you as soon as we can.</td>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0px; border-collapse: collapse; margin: 0px; padding: 0px; color: #ffffff; font-size: 1px; line-height: 1px; height: 16px; width: 64px;">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; background-color: #ffffff;">
<tbody>
<tr>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" width="64">&nbsp;</td>
<td class="m_-8504956366380948631Content" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; width: 472px; font-family: -apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Ubuntu,sans-serif; vertical-align: middle; color: #8898aa; font-size: 12px; line-height: 16px;">You\'re receiving this email because you made a purchase for a subscription plan on Music Engine.</td>
<td class="m_-8504956366380948631Spacer--gutter" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" width="64">&nbsp;</td>
</tr>
</tbody>
</table>
<table class="m_-8504956366380948631Section m_-8504956366380948631Section--last" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; background-color: #ffffff; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;" width="100%">
<tbody>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" height="64">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<table class="m_-8504956366380948631Divider--kill" style="border: 0; border-collapse: collapse; margin: 0; padding: 0;" width="100%">
<tbody>
<tr>
<td style="border: 0; border-collapse: collapse; margin: 0; padding: 0; color: #ffffff; font-size: 1px; line-height: 1px;" height="20">&nbsp;</td>
</tr>
</tbody>
</table>
<div class="yj6qo">&nbsp;</div>
<div class="adL">&nbsp;</div>
</div>',
                'created_at' => NULL,
                'updated_at' => '2020-07-29 09:11:00',
            ),
            14 => 
            array (
                'id' => 15,
                'type' => 'feedback',
                'description' => 'Configure e-mail message that is sent via the feedback form',
                'subject' => 'New feedback',
                'content' => '<p>Dear webmaster</p>
<p>&nbsp;</p>
<p>{{email}} has sent this letter.</p>
<p>He is feeling: {{feeling}}</p>
<p>Email is about: {{about}}</p>
<p>------------------------------------------------</p>
<p>Message text</p>
<p>------------------------------------------------</p>
<p>{{comment}}</p>
<p>------------------------------------------------</p>
<p>&nbsp;</p>
<p>IP address of the sender: {{ip}}</p>
<p>Email: {{email}}</p>
<p>&nbsp;</p>
<p>Sincerely,</p>
<p>NiNaCoder</p>',
                'created_at' => NULL,
                'updated_at' => '2020-07-22 01:21:35',
            ),
            15 =>
                array (
                    'id' => 16,
                    'type' => 'paymentHasBeenPaid',
                    'description' => 'Configure e-mail message that is sent to an artist about their payment\'s request has been approved',
                    'subject' => 'We recently sent you a payment',
                    'content' => '
<h1>We sent you money!</h1>
<p>Hi {{name}},</p>
<p>We just processed your payout for {{amount}}.</p>
<p>Happy Spending!.</p>
<p>Team NiNaCoder.</p>',

                    'created_at' => NULL,
                    'updated_at' => '2021-01-19 22:11:00',
                ),
            16 =>
                array (
                    'id' => 17,
                    'type' => 'paymentHasBeenDeclined',
                    'description' => 'Configure e-mail message that is sent to an artist about their payment has been rejected',
                    'subject' => 'Your payout was rejected',
                    'content' => '
<p>Your payout was rejected!</p>
<p>Hi {{name}},</p>
<p>Unfortunately your payout was rejected.</p>
<p>If you have any questions, please contact support!</p>
<p>Team NiNaCoder.</p>',
                    'created_at' => NULL,
                    'updated_at' => '2021-01-19 22:11:00',
                )
        ));
        
        
    }
}