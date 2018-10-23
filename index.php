<!DOCTYPE html>
<html>
  <head>
    <title>Joies de Supinfo</title>
    <contentType="text/html; charset=UTF-8">
    <meta http-equiv="refresh" content="30" />
  </head>

  <body style="background-color:#454545;">

    <style>
      .footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: #383838;
        color: white;
        text-align: center;
        border-top-width: 1px;
        border-top-color: #262626;
        border-top-style: solid;
      }
    </style>

    <?php
    // Ces deux fonctions gèrent les problèmes d'encodage des caractères dans le titre et dans l'url de l'image/vidéo
      function replace_unicode_escape_sequence($match) {
        return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
      }
      function unicode_decode($str) {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $str);
      }

      // On récupère le JSON contenant les informations de l'image/vidéo actuelle (lien, titre...)
      $url = 'https://www.joies-de-supinfo.fr/api/gif/random';
      $contents = file_get_contents($url);

      // Si on a pas de problèmes dans la récupération du JSON:
      if($contents !== false) {
        // Alors on découpe le JSON pour en récupérer les différents éléments (je sais que j'aurais pu utiliser un json_decode mais faites pas chier...)
        $exploded = explode('"', $contents);
        $title = $exploded[3];
        $type = $exploded[7];
        $url = $exploded[11];
        $title = utf8_decode(unicode_decode($title));

        echo "<center>";

        // On gère les différents formats de fichiers:
        // GIF:
        if ($type == "gif")
        {
          $url = str_replace("\/", "/", $url);
          $url = str_replace("//", "/", $url);
          $url = str_replace("http:/", "", $url);
          $url = str_replace("https:/", "", $url);
          $url = "http://".$url;

          echo "<img src=\"".$url."\" style=\"max-width: 80%; max-height: 70%; height: 75vh;\">";
        }
        // MP4 et WEBM
        elseif($type == "mp4" or $type == "webm")
        {
          $url = str_replace("\/", "/", $url);
          $url = str_replace("//", "/", $url);
          $url = str_replace("http:/", "", $url);
          $url = str_replace("https:/", "", $url);
          $url = "http://".$url;

          echo "<video controls src=\"".$url."\" autoplay loop style=\"max-width: 80%; max-height: 70%; height: 75vh;\"></video>";
        }

        echo "</center>";
      }

      // Si il y a une erreur, alors on affiche "ERROR" (oui, il y a déjà eu mieux en )
      else {
        echo "ERROR";
      }
    ?>

    <!-- Footer de la page avec les droits d'auteurs pour les joies de supinfo -->
    <div class="footer">
      <p>
        <h1>
          <b><?php echo $title; ?></b>
        </h1>
      </p>
      <p style="text-align: right; margin-right: 10px;">
        Powered by: <a href="https://www.joies-de-supinfo.fr" target="_blank" style="color: white;">Les joies de Supinfo</a> - Project by: <a href="http://td24.net/ThomasDazy" target="_blank" style="color: white;">Thomas Dazy</a>
      </p>
    </div>

  </body>
</html>
