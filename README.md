# RssWriter
(PHP 5 >= 5.2.0, PHP 7)

This class Create RSS2.0 Feed.

The usage is as follows.
```php
$rc = new RssWriter();
$rc->rssTitle = 'sample title';
$rc->rssLink = 'http://sample.com/';
$rc->rssDescription = 'sample description';
$rc->rssAtomLink = 'http://sample.com/rss/rss.xml';

$item1 = new RssWriterItem();
$item1->title = 'item title 1';
$item1->link = 'https://github.com/';
$item1->description = 'item description 1';
$item1->setPudDate(date('Y/m/d H:i:s'));
$item1->guid = '0001';
$rc->addItem($item1);

$item2 = new RssWriterItem();
$item2->title = 'item title 2';
$item2->link = 'https://github.com/';
$item2->description = 'item description 2';
$item2->setPudDate(date('Y/m/d H:i:s'));
$item2->guid = '0002';
$rc->addItem($item2);

echo $rc->saveRss();
```

Default RSS Language is 'ja', and Encode is 'UTF-8'.
If you want to change. please set parameter to RssWriter class.
```php
$rc = new RssWriter();
$rc->rssLang = 'en';
$rc->xmlEncoding = 'EUC';
```

If you want to format output xml.
set RSSWriter::formatFlg true.
```php
$rc = new RssWriter();
$rc->formatFlg = true;
```

