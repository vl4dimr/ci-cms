<?php 
echo '<?xml version="1.0" encoding="utf-8"?>' . "
";
?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">

    <channel>
    
    <title><?php echo $feed_name; ?></title>

    <link><?php echo $feed_url; ?></link>
    <description><?php echo $page_description; ?></description>
    <dc:language><?php echo $page_language; ?></dc:language>
    <dc:creator><?php echo $creator_email; ?></dc:creator>

    <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
    <admin:generatorAgent rdf:resource="http://solaitra.tuxfamily.org/" />

    <?php foreach($contents as $item): ?>
    
        <item>

          <title><?php echo $item['title']; ?></title>
          <link><?php echo $item['url'] ?></link>
          <guid><?php echo $item['url'] ?></guid>
		  <?php if(isset($item['author'])) : ?>
          <author><?php echo $item['author'] ?></author>
		  <?php endif; ?>
          <description><![CDATA[<?php echo $item['body'] ?>]]></description>
      <pubDate><?php echo date ('r', $item['date']);?></pubDate>
	  	<?php if(isset($item['img'])) :?>
			<enclosure url="<?php echo $item['img'] ?>" length="" type="image/jpeg"/>
			<media:thumbnail xmlns:media='http://search.yahoo.com/mrss/' url='<?php echo $item['img'] ?>' height='100' width='100'/>
		<?php endif; ?>
        </item>

        
    <?php endforeach; ?>
    
    </channel></rss>  