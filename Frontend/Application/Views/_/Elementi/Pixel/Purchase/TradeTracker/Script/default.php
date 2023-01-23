<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<script type="text/javascript">
 var ttConversionOptions = ttConversionOptions || [];
 ttConversionOptions.push(<?php echo F::jsonEncode($jsonArray);?>);
</script>
<script type="text/javascript">
  // No editing needed below this line.
 (function(ttConversionOptions) {
  var campaignID = 'campaignID' in ttConversionOptions ? ttConversionOptions.campaignID : ('length' in ttConversionOptions && ttConversionOptions.length ? ttConversionOptions[0].campaignID : null);
  var tt = document.createElement('script'); tt.type = 'text/javascript'; tt.async = true; tt.src = '//tm.tradetracker.net/conversion?s=' + encodeURIComponent(campaignID) + '&t=m';
  var s = document.getElementsByTagName('script'); s = s[s.length - 1]; s.parentNode.insertBefore(tt, s);
 })(ttConversionOptions);
</script>
