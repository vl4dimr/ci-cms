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
    
    <title><?php echo $title; ?></title>

    <link><?php echo site_url('news/tag'); ?></link>
    <description><?php echo $this->system->meta_description ?></description>
    <dc:language><?php echo $this->user->lang ; ?></dc:language>
    <dc:creator><?php echo isset($this->system->admin_email)? $this->system->admin_email : "" ?></dc:creator>

    <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
    <admin:generatorAgent rdf:resource="http://solaitra.tuxfamily.org/" />

    <?php foreach($rows as $row): ?>
    
        <item>

          <title><?php echo $row['tag']; ?></title>
          <link><?php echo site_url( '/news/tag/' . $row['uri']); ?></link>
          <guid><?php echo site_url( '/news/tag/' . $row['uri']) ?></guid>
          <description><![CDATA[<?php echo $row['tag'] ?>]]></description>
        </item>

        
    <?php endforeach; ?>
    
    </channel></rss>  