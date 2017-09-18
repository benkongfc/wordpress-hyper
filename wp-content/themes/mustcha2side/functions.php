<?php
//the GNU General Public License
require 'Mustache/Autoloader.php';
Mustache_Autoloader::register();

function fctr_scripts() {
  /* bootstrap */
  wp_enqueue_style('css', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
  wp_enqueue_script('reviews-bootstrap', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js", array('jquery'), false, true);

  wp_enqueue_style('style-css', get_stylesheet_directory_uri() . '/style.css');
  wp_enqueue_script('mustache-js', get_stylesheet_directory_uri() . '/dist/mustache.js', array('jquery'), '20170522', true);
  wp_enqueue_script('site-js', get_stylesheet_directory_uri() . '/dist/site.js', array('jquery'), '20170523', true);
}

add_action('wp_enqueue_scripts', 'fctr_scripts');
 
//autorender
function echoAllTemplates(){
  $files = scandir(__DIR__);
  foreach($files as $file){
    if(strpos($file, '.mustache') !== FALSE){  ?>
    <script mustache-id="<?php echo $file; ?>" type="text/template">
<?php 
  echo str_replace("</script>", '</re_script>', file_get_contents(__DIR__ . "/$file")); 
?>
    </script>
<?php     
    }
  }
}

function phpRender(&$data){
  foreach($data['partials'] as &$p){
    $p = file_get_contents(__DIR__ . "/$p");
  }
  $mustache = new Mustache_Engine(array("partials" => $data['partials']));
  foreach($data['templates'] as $t){
      echo $mustache->render(file_get_contents(__DIR__ . "/$t"), $data);
  }
}

function autoRender(&$data){
  //js rendering
  if(!empty($_POST['in_ajax'])){
    wp_send_json($data); //die here!!!
  }

  //php render
  get_header();
  $data['contentJson'] = json_encode($data);
  phpRender($data);
  get_footer();
}
