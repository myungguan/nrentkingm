<?php
/**
 * Created by IntelliJ IDEA.
 * User: smkang
 * Date: 2018-01-15
 * Time: 18:02
 */
?>

<!-- Channel Plugin Scripts -->
<script>
window.channelPluginSettings = {
    "pluginKey": "36549586-bdc3-4054-938b-82b5d30f2e07"
  };
  (function() {
      var node = document.createElement('div');
      node.id = 'ch-plugin';
      document.body.appendChild(node);
      var async_load = function() {
          var s = document.createElement('script');
          s.type = 'text/javascript';
          s.async = true;
          s.src = '//cdn.channel.io/plugin/ch-plugin-web.js';
          s.charset = 'UTF-8';
          var x = document.getElementsByTagName('script')[0];
          x.parentNode.insertBefore(s, x);
      };
      if (window.attachEvent) {
          window.attachEvent('onload', async_load);
      } else {
          window.addEventListener('load', async_load, false);
      }
  })();
</script>
<!-- End Channel Plugin -->