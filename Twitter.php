<?php
/**
 * Usage:
 $this->widget('ext.twitter.Twitter',array(
	    'username'=>gl('twitter'),
	    'numberTweets'=>10,
	    'widgetId'=>'home',
	));
 */
 
require_once(dirname(__FILE__) . '/TweetPHP.php'); //Path to twitteroauth library

class Twitter extends CWidget
{
	public $username=null;
	public $numberTweets = 10;
	
	public $consumerkey = "";
	public $consumersecret = "";
	public $accesstoken = "";
	public $accesstokensecret = "";
	
	public $widgetId='default';
	
	public function init(){
        if (!$this->username){
            throw new CException("Username cannot be empty!");
        }
    }

	public function run()
	{
		$options=array(
		  'consumer_key'          => $this->consumerkey,
          'consumer_secret'       => $this->consumersecret,
          'access_token'          => $this->accesstoken,
          'access_token_secret'   => $this->accesstokensecret,
          'twitter_screen_name'   => $this->username,		
          'cachetime'             => 60 * 60, // Seconds to cache feed (1 hour).
          'tweets_to_display'     => $this->numberTweets, // How many tweets to fetch
          'ignore_replies'        => true, // Ignore @replies
          'ignore_retweets'       => true, // Ignore retweets
          'twitter_style_dates'   => false, // Use twitter style dates e.g. 2 hours ago
          'date_format'           => 'g:i A M jS', // The dafult date format e.g. 12:08 PM Jun 12th
          'format'                => 'html', // Can be 'html' or 'array'
          'twitter_wrap_open'     => '<ul id="twitter">',
          'twitter_wrap_close'    => '</ul>',
          'tweet_wrap_open'       => '<li><span class="status">',
          'meta_wrap_open'        => '</span><span class="meta"> ',
          'meta_wrap_close'       => '</span>',
          'tweet_wrap_close'      => '</li>',
          'error_message'         => 'Oops, our twitter feed is unavailable right now.',
          'error_link_text'       => 'Follow us on Twitter',
          'cache_file'            => $this->createCacheFile(), // Where on the server to save the cached formatted tweets
          'cache_file_raw'        => dirname(__FILE__).'/cache/twitter-array.txt', // Where on the server to save the cached raw tweets
		);
		$TweetPHP=new TweetPHP($options);

		echo $TweetPHP->get_tweet_list();
	}
	
	public function createCacheFile()
	{
		$cache_path = dirname(__FILE__).'/cache/';
		if(!is_dir($cache_path)) mkdir($cache_path, 0777, true);
		
		$filename = $cache_path.'twitter-'.$this->widgetId.'.txt';
		
		if (!file_exists($filename)) 
		{
			$content = "";
			$fp = fopen($filename,"wb");
			fwrite($fp,$content);
			fclose($fp);
		}
		
		return $filename;
	}
}