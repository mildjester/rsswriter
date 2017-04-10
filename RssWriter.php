<?php
/**
 * Create Simple RSS Feed
 * @author mildjester
 *
 */
class RssWriter {
	public $formatFlg = false;
	public $xmlVer = '1.0';
	public $xmlEncoding = 'UTF-8';
	public $rssTitle = 'insert rss title here.';
	public $rssLink = '';
	public $rssAtomLink = '';
	public $rssDescription = 'This is RSS description';
	public $rssLang = 'ja';
	public $rssGenerator = 'CMSjp ver.1.0';
	private $rssUpdatetime = null;
	private $itemList = array();
	
	public function __construct(){
		$dt = new DateTime();
		$this->rssUpdatetime = $dt->format(DateTime::RSS);
	}
	/**
	 * Set Rss Updatetime
	 * @param $time date string supported by PHP.
	 */
	public function setRssUpdatetime($time) {
		if (strtotime($date) !== FALSE) {
			$dt = new DateTime($time);
			$this->rssUpdatetime = $dt->format(DateTime::RSS);
		} else {
			throw new Exception('RssWriter::setRssUpdatetime param must be Date Format String.');
		}
	}
	/**
	 * Add item feed
	 * @param $item RssWriterItem
	 */
	public function addItem($item) {
		if (get_class($item) === 'RssWriterItem') {
			$this->itemList[] = $item;
		} else {
			throw new Exception('RssWriter::addItem can set only RssWriterItem.');
		}
	}
	
	/**
	 * Output RSS Feed as String
	 * @return RSS Feed
	 */
	public function saveRss(){
		$xml = new DOMDocument($this->xmlVer, $this->xmlEncoding);
		$xml->formatOutput = $this->formatFlg;
		
		$rss = $xml->createElement("rss");
		$rss_node = $xml->appendChild($rss); 
		$rss_node->setAttribute("xmlns:atom","http://www.w3.org/2005/Atom");
		$rss_node->setAttribute("version","2.0");
		
		$channel = $xml->createElement("channel");
		$channel_node = $rss_node->appendChild($channel);
		$channelParams = [
			'language' => 'rssLang',
			'title' => 'rssTitle',
			'link' => 'rssLink',
			'description' => 'rssDescription',
			'generator' => 'rssGenerator',
			'pubDate' => 'rssUpdatetime',
			'lastBuildDate' => 'rssUpdatetime'
		];
		foreach ($channelParams as $name => $param) {
			$tmpElm = $xml->createElement($name);
			$tmpElm->appendChild(new DOMText($this->$param));
			$channel_node->appendChild($tmpElm);
		}
		if (isset($this->rssAtomLink) && strlen($this->rssAtomLink) > 0) {
			$atomlink = $xml->createElement("atom:link");
			$atomlink_node = $channel_node->appendChild($atomlink);
			$atomlink_node->setAttribute("rel","self");
			$atomlink_node->setAttribute("href",$this->rssAtomLink);
			$atomlink_node->setAttribute("type","application/rss+xml");
		}
		
		foreach ($this->itemList as $childItem) {
			$item = $xml->createElement("item");
			$item_node = $channel_node->appendChild($item);
			foreach ($childItem as $prop => $val) {
				if (isset($val) && strlen($val) > 0) {
					$tmpElm = $xml->createElement($prop);
					if (in_array($prop, ['title', 'description'])) {
						$tmpElm->appendChild($xml->createCDATASection($val));
					} else {
						$tmpElm->appendChild(new DOMText($val));
					}
					$tmp_node = $item_node->appendChild($tmpElm);
					if ($prop === 'guid') {
						$tmp_node->setAttribute('isPermaLink','false');
					}
				}
			}
		}
		return $xml->saveXML();
	}
}

/**
 * Item class to RSS Feed
 * @author mildjester
 *
 */
class RssWriterItem {
	public $title;
	public $link;
	public $guid;
	public $pubDate;
	public $description;
	/**
	 * Set item pubDate.
	 * @param $date date string supported by PHP.
	 */
	public function setPubDate($date) {
		if (strtotime($date) !== FALSE) {
			$dt = new DateTime($date);
			$this->pubDate = $dt->format(DateTime::RSS);
		} else {
			throw new Exception('RssWriterItem::setPubDate param must be Date Format String.');
		}
	}
	
}
