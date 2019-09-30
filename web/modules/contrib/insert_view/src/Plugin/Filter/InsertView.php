<?php

/**
 * @file
 * Contains \Drupal\insert_view\Plugin\Filter\InsertView.
 */

namespace Drupal\insert_view\Plugin\Filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\Core\Url;

/**
 * Provides a filter for insert view.
 *
 * @Filter(
 *   id = "insert_view",
 *   module = "insert_view",
 *   title = @Translation("Insert View"),
 *   description = @Translation("Allows to embed views using the simple syntax: [view:name=display=args]"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class InsertView extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    if (!empty($text)) {
      $text = _insert_view_substitute_tags($text);
    }

    return new FilterProcessResult($text);
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {
    if ($long) {
      return '<br />' . t(
'<dl>
<dt>Insert view filter allows to embed views using tags. The tag syntax is relatively simple: [view:name=display=args]</dt>
<dt>For example [view:tracker=page=1] says, embed a view named "tracker", use the "page" display, and supply the argument "1".</dt>
<dt>The <em>display</em> and <em>args</em> parameters can be omitted. If the display is left empty, the view\'s default display is used.</dt>
<dt>Multiple arguments are separated with slash. The <em>args</em> format is the same as used in the URL (or view preview screen).</dt>
</dl>
Valid examples:
<dl>
<dt>[view:my_view]</dt>
<dt>[view:my_view=my_display]</dt>
<dt>[view:my_view=my_display=arg1/arg2/arg3]</dt>
<dt>[view:my_view==arg1/arg2/arg3]</dt>
</dl>') . '<br />';
    }
    else {
      return t('You may use [view:<em>name=display=args</em>] tags to display views.');
    }
  }
}

