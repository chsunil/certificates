<?php
/**
 * Expects query var 'client_nav_args' => [
 *   'stages'      => [ slug => [title,group,next], … ],
 *   'stage'       => current slug,
 *   'real_post_id'=> int
 * ]
 */
$args      = get_query_var('client_nav_args', []);
$stages    = $args['stages']      ?? [];
$current   = $args['stage']       ?? '';
$real_id   = $args['real_post_id']?? 0;

$keys      = array_keys($stages);
$idx       = array_search($current, $keys, true);
$visible   = array_slice($keys, 0, $idx+1);
?>
<ul class="nav nav-pills flex-nowrap mx-0">
  <?php foreach($visible as $slug):
    $step    = $stages[$slug];
    $classes = ['nav-link'];
    if ($slug === $current)        $classes[] = 'active';
    if ( empty($step['group']) )    $classes[] = 'disabled';

    // build URL (use 'create' for new_post_id)
    $post_id = $real_id ?: 'create';
    $url = esc_url( add_query_arg(
           ['new_post_id'=>$post_id,'stage'=>$slug],
           get_permalink()
         ));
  ?>
    <li class="nav-item flex-shrink-0">
      <a class="<?php echo esc_attr(join(' ',$classes));?>"
         href="<?php echo $url; ?>">
        <?php echo esc_html($step['title']); ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
